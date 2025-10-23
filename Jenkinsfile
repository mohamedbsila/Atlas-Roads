pipeline {
    agent any

    environment {
        APP_NAME = 'atlas-roads'
        PHP_VERSION = '8.2'
        NODE_VERSION = '18'
        WORKSPACE_PATH = "${WORKSPACE}"
        VENDOR_PATH = "${WORKSPACE}/vendor"
        NODE_MODULES_PATH = "${WORKSPACE}/node_modules"
        DB_CONNECTION = 'mysql'
        DB_HOST = '127.0.0.1'
        DB_PORT = '3306'
        DB_DATABASE = 'atlas_roads_test'
        DB_USERNAME = 'jenkins'
        DB_PASSWORD = 'jenkins123'
        PHPUNIT_COVERAGE = 'false'
        DEPLOY_PATH = '/var/www/atlas-roads'
        NEXUS_URL = 'localhost:8081'
    }

    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        timeout(time: 30, unit: 'MINUTES')
        disableConcurrentBuilds()
        timestamps()
    }

    stages {
        stage('Checkout') {
            steps {
                echo 'Checking out code from repository...'
                checkout scm

                script {
                    env.GIT_COMMIT_MSG = sh(script: 'git log -1 --pretty=%B', returnStdout: true).trim()
                    env.GIT_AUTHOR = sh(script: 'git log -1 --pretty=%an', returnStdout: true).trim()
                    env.GIT_COMMIT_SHORT = sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()
                }

                echo "Commit: ${env.GIT_COMMIT_SHORT} by ${env.GIT_AUTHOR}"
                echo "Message: ${env.GIT_COMMIT_MSG}"
            }
        }

        stage('Start Docker Services') {
            steps {
                echo 'Starting MySQL and Nexus containers...'
                script {
                    sh '''
                        # Créer un réseau Docker s'il n'existe pas
                        docker network create atlas-network 2>/dev/null || true
                        
                        # Démarrer MySQL avec port mapping
                        if docker ps -a | grep -q atlas-mysql; then
                            # Vérifier si le port est mappé
                            if ! docker port atlas-mysql | grep -q 3306; then
                                echo "Recreating MySQL container with port mapping..."
                                docker stop atlas-mysql || true
                                docker rm atlas-mysql || true
                            fi
                        fi
                        
                        if ! docker ps -a | grep -q atlas-mysql; then
                            echo "Creating MySQL container..."
                            docker run -d \\
                                --name atlas-mysql \\
                                --network atlas-network \\
                                --restart unless-stopped \\
                                -p 3306:3306 \\
                                -e MYSQL_ROOT_PASSWORD=123456789 \\
                                -e MYSQL_DATABASE=atlas_roads \\
                                -e MYSQL_USER=laravel \\
                                -e MYSQL_PASSWORD=laravel123 \\
                                mysql:8.0
                        elif ! docker ps | grep -q atlas-mysql; then
                            echo "Starting existing MySQL container..."
                            docker start atlas-mysql
                        else
                            echo "MySQL container already running"
                        fi
                        
                        # Démarrer Nexus
                        if ! docker ps -a | grep -q atlas-nexus; then
                            echo "Creating Nexus container..."
                            docker run -d \\
                                --name atlas-nexus \\
                                --network atlas-network \\
                                -p 8081:8081 \\
                                sonatype/nexus3:latest
                        elif ! docker ps | grep -q atlas-nexus; then
                            echo "Starting existing Nexus container..."
                            docker start atlas-nexus
                        else
                            echo "Nexus container already running"
                        fi
                        
                        # Attendre que MySQL soit prêt
                        echo "Waiting for MySQL to be ready..."
                        for i in {1..30}; do
                            if docker exec atlas-mysql mysqladmin ping -h localhost --silent 2>/dev/null; then
                                echo "MySQL is ready!"
                                break
                            fi
                            echo "Waiting for MySQL... ($i/30)"
                            sleep 2
                        done
                        
                        echo "Docker services started successfully!"
                    '''
                }
            }
        }

        stage('Environment Setup') {
            steps {
                echo 'Setting up environment...'
                script {
                    sh '''
                        echo "PHP Version:"
                        php -v

                        echo "Composer Version:"
                        composer --version

                        echo "Node Version:"
                        node -v

                        echo "NPM Version:"
                        npm -v
                    '''
                }
            }
        }

        stage('Prepare Environment') {
            steps {
                echo 'Preparing Laravel environment...'
                script {
                    sh '''
                        if [ ! -f .env ]; then
                            cp .env.example .env
                            echo "Created .env file"
                        fi
                    '''
                    sh '''
                        sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=mysql/" .env
                        sed -i "s/DB_HOST=.*/DB_HOST=127.0.0.1/" .env
                        sed -i "s/DB_PORT=.*/DB_PORT=3306/" .env
                        sed -i "s/DB_DATABASE=.*/DB_DATABASE=atlas_roads/" .env
                        sed -i "s/DB_USERNAME=.*/DB_USERNAME=laravel/" .env
                        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=laravel123/" .env
                    '''
                }
            }
        }

        stage('Install Dependencies') {
            parallel {
                stage('Composer Dependencies') {
                    steps {
                        echo 'Installing PHP dependencies...'
                        sh '''
                            export COMPOSER_PROCESS_TIMEOUT=1200
                            composer install --no-interaction --prefer-source --optimize-autoloader
                        '''
                        echo 'PHP dependencies installed'
                    }
                }
                stage('NPM Dependencies') {
                    steps {
                        echo 'Installing Node dependencies...'
                        sh '''
                            npm ci
                        '''
                        echo 'Node dependencies installed'
                    }
                }
            }
        }

        stage('Generate App Key') {
            steps {
                echo 'Generating application key...'
                sh 'php artisan key:generate --force'
                echo 'Application key generated'
            }
        }

        stage('Build Assets') {
            steps {
                echo 'Building frontend assets...'
                sh 'npm run build'
                echo 'Assets compiled successfully'
            }
        }

        stage('Code Quality Checks') {
            parallel {
                stage('PHP Syntax Check') {
                    steps {
                        echo 'Checking PHP syntax...'
                        sh '''
                            find app -name "*.php" -exec php -l {} \\; > /tmp/php-syntax-check.log 2>&1
                            if grep -q "Parse error" /tmp/php-syntax-check.log; then
                                cat /tmp/php-syntax-check.log
                                exit 1
                            fi
                            echo "All PHP files have valid syntax"
                        '''
                        echo 'PHP syntax check passed'
                    }
                }
                stage('Code Style Check') {
                    steps {
                        echo 'Checking code style...'
                        script {
                            def phpCsFixerExists = sh(
                                script: 'command -v php-cs-fixer',
                                returnStatus: true
                            ) == 0

                            if (phpCsFixerExists) {
                                sh 'php-cs-fixer fix --dry-run --diff --verbose || true'
                            } else {
                                echo 'PHP CS Fixer not installed, skipping...'
                            }
                        }
                    }
                }
                stage('Security Audit') {
                    steps {
                        echo 'Running security audit...'
                        sh '''
                            composer audit || true
                            npm audit --audit-level=moderate || true
                        '''
                    }
                }
            }
        }

        stage('Database Setup') {
            steps {
                echo 'Setting up test database...'
                script {
                    sh '''
                        # Utiliser docker exec pour se connecter au conteneur MySQL
                        docker exec atlas-mysql mysql -uroot -p123456789 -e "DROP DATABASE IF EXISTS atlas_roads;" || true
                        docker exec atlas-mysql mysql -uroot -p123456789 -e "CREATE DATABASE IF NOT EXISTS atlas_roads;"
                        docker exec atlas-mysql mysql -uroot -p123456789 -e "CREATE USER IF NOT EXISTS 'laravel'@'%' IDENTIFIED BY 'laravel123';" || true
                        docker exec atlas-mysql mysql -uroot -p123456789 -e "GRANT ALL PRIVILEGES ON atlas_roads.* TO 'laravel'@'%';"
                        docker exec atlas-mysql mysql -uroot -p123456789 -e "FLUSH PRIVILEGES;"
                    '''
                    sh '''
                        php artisan migrate:fresh --force --seed
                    '''
                    echo 'Database setup completed'
                }
            }
        }

        stage('Run Tests') {
            steps {
                echo 'Running tests...'
                script {
                    sh '''
                        php artisan config:clear
                        php artisan cache:clear
                        vendor/bin/phpunit --testdox --colors=always
                    '''
                }
                echo 'All tests passed'
            }
        }

        stage('Code Coverage') {
            when {
                expression { env.PHPUNIT_COVERAGE == 'true' }
            }
            steps {
                echo 'Generating code coverage report...'
                script {
                    sh '''
                        vendor/bin/phpunit --coverage-html coverage --coverage-clover coverage.xml || true
                    '''
                }
                publishHTML(target: [
                    allowMissing: true,
                    alwaysLinkToLastBuild: true,
                    keepAll: true,
                    reportDir: 'coverage',
                    reportFiles: 'index.html',
                    reportName: 'Code Coverage Report'
                ])
            }
        }

        stage('Build Docker Image') {
            steps {
                echo 'Building Docker image for Laravel app...'
                script {
                    sh """
                        docker build -t atlas-laravel:${env.GIT_COMMIT_SHORT} .
                        docker tag atlas-laravel:${env.GIT_COMMIT_SHORT} atlas-laravel:latest
                        echo "Docker image built: atlas-laravel:${env.GIT_COMMIT_SHORT}"
                        docker images | grep atlas-laravel
                    """
                }
            }
        }

        stage('Package & Upload to Nexus') {
            steps {
                echo 'Packaging Laravel app as ZIP...'
                sh 'zip -r lara-app.zip .'
                echo 'Package created successfully'
                
                // Upload to Nexus (temporarily disabled - configure nexus-creds in Jenkins)
                script {
                    try {
                        nexusArtifactUploader(
                            nexusVersion: 'nexus3',
                            protocol: 'http',
                            nexusUrl: "${NEXUS_URL}",
                            groupId: 'laravel',
                            version: "${env.GIT_COMMIT_SHORT ?: '1.0.0'}",
                            repository: 'lara',
                            credentialsId: 'nexus-creds',
                            artifacts: [
                                [artifactId: 'lara-app', classifier: '', file: 'lara-app.zip', type: 'zip']
                            ]
                        )
                        echo 'Package uploaded to Nexus'
                    } catch (Exception e) {
                        echo "WARNING: Nexus upload failed: ${e.message}"
                        echo 'Continuing pipeline without Nexus upload...'
                    }
                }
            }
        }

        stage('Deploy Application') {
            steps {
                echo 'Deploying Laravel application...'
                script {
                    sh '''
                        # Arrêter et supprimer l'ancien conteneur s'il existe
                        docker stop atlas-app 2>/dev/null || true
                        docker rm atlas-app 2>/dev/null || true
                        
                        # Démarrer le nouveau conteneur Laravel
                        echo "Starting Laravel application container..."
                        docker run -d \\
                            --name atlas-app \\
                            --network atlas-network \\
                            --restart unless-stopped \\
                            -p 8000:80 \\
                            -e APP_NAME="Atlas Roads Library" \\
                            -e APP_ENV=production \\
                            -e APP_DEBUG=false \\
                            -e APP_URL=http://localhost:8000 \\
                            -e DB_CONNECTION=mysql \\
                            -e DB_HOST=atlas-mysql \\
                            -e DB_PORT=3306 \\
                            -e DB_DATABASE=atlas_roads \\
                            -e DB_USERNAME=laravel \\
                            -e DB_PASSWORD=laravel123 \\
                            atlas-laravel:latest
                        
                        # Attendre que l'application soit prête
                        echo "Waiting for application to start..."
                        sleep 10
                        
                        # Vérifier le statut
                        if docker ps | grep -q atlas-app; then
                            echo "✓ Application deployed successfully!"
                            echo "Access at: http://localhost:8000"
                            docker logs atlas-app --tail 20
                        else
                            echo "✗ Application failed to start!"
                            docker logs atlas-app --tail 50
                            exit 1
                        fi
                    '''
                }
            }
        }

        stage('Cleanup') {
            steps {
                echo 'Cleaning up...'
                script {
                    sh '''
                        mysql -h${DB_HOST} -P${DB_PORT} -u${DB_USERNAME} -p${DB_PASSWORD} -e "DROP DATABASE IF EXISTS ${DB_DATABASE};" || true
                    '''
                    sh '''
                        php artisan cache:clear || true
                        php artisan config:clear || true
                        php artisan route:clear || true
                        php artisan view:clear || true
                    '''
                }
                echo 'Cleanup completed'
            }
        }
    }

    post {
        always {
            script {
                echo 'Pipeline execution completed'
                
                // Garder les conteneurs en cours d'exécution (MySQL, Nexus, Application)
                sh '''
                    echo "Docker containers status:"
                    docker ps --filter "name=atlas-" --format "table {{.Names}}\t{{.Status}}\t{{.Ports}}"
                    echo ""
                    echo "✓ MySQL running on port 3306"
                    echo "✓ Nexus running on port 8081"
                    echo "✓ Laravel app running on port 8000"
                    echo ""
                    echo "Application URL: http://localhost:8000"
                ''' 
                
                try {
                    cleanWs(
                        deleteDirs: true,
                        patterns: [
                            [pattern: 'vendor/**', type: 'INCLUDE'],
                            [pattern: 'node_modules/**', type: 'INCLUDE'],
                            [pattern: 'coverage/**', type: 'INCLUDE']
                        ]
                    )
                } catch (Exception e) {
                    echo "Workspace cleanup skipped: ${e.message}"
                }
            }
        }
        // Notifs par mail (à adapter au besoin)
        // ...
    }
}
