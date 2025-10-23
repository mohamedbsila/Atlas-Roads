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
        NEXUS_URL = 'http://localhost:8081'
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
                echo 'Starting MySQL and Nexus Docker containers...'
                script {
                    sh '''
                        # Arrêter les anciens conteneurs s'ils existent
                        docker-compose down || true
                        
                        # Démarrer les services
                        docker-compose up -d
                        
                        # Attendre que MySQL soit prêt
                        echo "Waiting for MySQL to be ready..."
                        for i in {1..30}; do
                            if docker-compose exec -T mysql mysqladmin ping -h localhost --silent; then
                                echo "MySQL is ready!"
                                break
                            fi
                            echo "Waiting for MySQL... ($i/30)"
                            sleep 2
                        done
                        
                        # Vérifier que Nexus démarre (il prend du temps)
                        echo "Nexus is starting in background..."
                        docker-compose ps
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
                        sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=${DB_CONNECTION}/" .env
                        sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
                        sed -i "s/DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
                        sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
                        sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
                        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env
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
                        mysql -h${DB_HOST} -P${DB_PORT} -u${DB_USERNAME} -p${DB_PASSWORD} -e "DROP DATABASE IF EXISTS ${DB_DATABASE};" || true
                        mysql -h${DB_HOST} -P${DB_PORT} -u${DB_USERNAME} -p${DB_PASSWORD} -e "CREATE DATABASE IF NOT EXISTS ${DB_DATABASE};"
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

        stage('Package & Upload to Nexus') {
            steps {
                echo 'Packaging Laravel app as ZIP...'
                sh 'zip -r lara-app.zip .'
                echo 'Uploading package to Nexus...'
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
                echo 'Package uploaded to Nexus (lara)'
            }
        }

        // Déploiement (à personnaliser si besoin)
        // stage('Deploy to Staging') { ... }
        // stage('Deploy to Production') { ... }

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
                
                // Arrêter les conteneurs Docker (optionnel - commentez si vous voulez les garder)
                sh '''
                    echo "Stopping Docker containers..."
                    docker-compose down || true
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
