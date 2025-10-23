pipeline {
    agent any
<<<<<<< HEAD
    
    environment {
        // Project Configuration
        APP_NAME = 'atlas-roads'
        PHP_VERSION = '8.2'
        NODE_VERSION = '18'
        
        // Paths
        WORKSPACE_PATH = "${WORKSPACE}"
        VENDOR_PATH = "${WORKSPACE}/vendor"
        NODE_MODULES_PATH = "${WORKSPACE}/node_modules"
        
        // Database Configuration (use Jenkins credentials)
=======

    environment {
        APP_NAME = 'atlas-roads'
        PHP_VERSION = '8.2'
        NODE_VERSION = '18'
        WORKSPACE_PATH = "${WORKSPACE}"
        VENDOR_PATH = "${WORKSPACE}/vendor"
        NODE_MODULES_PATH = "${WORKSPACE}/node_modules"
>>>>>>> origin/complet
        DB_CONNECTION = 'mysql'
        DB_HOST = '127.0.0.1'
        DB_PORT = '3306'
        DB_DATABASE = 'atlas_roads_test'
<<<<<<< HEAD
        
        // Testing
        PHPUNIT_COVERAGE = 'false'
        
        // Deployment (configure as needed - optional)
        // DEPLOY_SERVER = credentials('deploy-server')
        DEPLOY_PATH = '/var/www/atlas-roads'
    }
    
    options {
        // Keep last 10 builds
        buildDiscarder(logRotator(numToKeepStr: '10'))
        
        // Timeout for entire pipeline
        timeout(time: 30, unit: 'MINUTES')
        
        // Disable concurrent builds
        disableConcurrentBuilds()
        
        // Timestamps in console output
        timestamps()
    }
    
=======
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

>>>>>>> origin/complet
    stages {
        stage('Checkout') {
            steps {
                echo 'Checking out code from repository...'
                checkout scm
<<<<<<< HEAD
                
                script {
                    // Get commit info
=======

                script {
>>>>>>> origin/complet
                    env.GIT_COMMIT_MSG = sh(script: 'git log -1 --pretty=%B', returnStdout: true).trim()
                    env.GIT_AUTHOR = sh(script: 'git log -1 --pretty=%an', returnStdout: true).trim()
                    env.GIT_COMMIT_SHORT = sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()
                }
<<<<<<< HEAD
                
=======

>>>>>>> origin/complet
                echo "Commit: ${env.GIT_COMMIT_SHORT} by ${env.GIT_AUTHOR}"
                echo "Message: ${env.GIT_COMMIT_MSG}"
            }
        }
<<<<<<< HEAD
        
        stage('Environment Setup') {
            steps {
                echo 'Setting up environment...'
                
=======

        stage('Start Docker Services') {
            steps {
                echo 'Starting MySQL and Nexus containers...'
                script {
                    sh '''
                        # Créer un réseau Docker s'il n'existe pas
                        docker network create atlas-network 2>/dev/null || true
                        
                        # Démarrer MySQL
                        if ! docker ps -a | grep -q atlas-mysql; then
                            echo "Creating MySQL container..."
                            docker run -d \\
                                --name atlas-mysql \\
                                --network atlas-network \\
                                -p 3306:3306 \\
                                -e MYSQL_ROOT_PASSWORD=rootpass \\
                                -e MYSQL_DATABASE=atlas_roads_test \\
                                -e MYSQL_USER=jenkins \\
                                -e MYSQL_PASSWORD=jenkins123 \\
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
>>>>>>> origin/complet
                script {
                    sh '''
                        echo "PHP Version:"
                        php -v
<<<<<<< HEAD
                        
                        echo "Composer Version:"
                        composer --version
                        
                        echo "Node Version:"
                        node -v
                        
=======

                        echo "Composer Version:"
                        composer --version

                        echo "Node Version:"
                        node -v

>>>>>>> origin/complet
                        echo "NPM Version:"
                        npm -v
                    '''
                }
            }
        }
<<<<<<< HEAD
        
        stage('Prepare Environment') {
            steps {
                echo 'Preparing Laravel environment...'
                
                script {
                    // Copy .env.example to .env for testing
=======

        stage('Prepare Environment') {
            steps {
                echo 'Preparing Laravel environment...'
                script {
>>>>>>> origin/complet
                    sh '''
                        if [ ! -f .env ]; then
                            cp .env.example .env
                            echo "Created .env file"
                        fi
                    '''
<<<<<<< HEAD
                    
                    // Update .env with test database credentials
                    // For empty password, we don't use withCredentials
=======
>>>>>>> origin/complet
                    sh '''
                        sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=${DB_CONNECTION}/" .env
                        sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
                        sed -i "s/DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
                        sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
<<<<<<< HEAD
                        sed -i "s/DB_USERNAME=.*/DB_USERNAME=root/" .env
                        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=/" .env
=======
                        sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
                        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env
>>>>>>> origin/complet
                    '''
                }
            }
        }
<<<<<<< HEAD
        
=======

>>>>>>> origin/complet
        stage('Install Dependencies') {
            parallel {
                stage('Composer Dependencies') {
                    steps {
                        echo 'Installing PHP dependencies...'
                        sh '''
<<<<<<< HEAD
                            # Increase timeout and use source instead of dist for slow connections
=======
>>>>>>> origin/complet
                            export COMPOSER_PROCESS_TIMEOUT=1200
                            composer install --no-interaction --prefer-source --optimize-autoloader
                        '''
                        echo 'PHP dependencies installed'
                    }
                }
<<<<<<< HEAD
                
=======
>>>>>>> origin/complet
                stage('NPM Dependencies') {
                    steps {
                        echo 'Installing Node dependencies...'
                        sh '''
<<<<<<< HEAD
                            npm ci --silent
=======
                            npm ci
>>>>>>> origin/complet
                        '''
                        echo 'Node dependencies installed'
                    }
                }
            }
        }
<<<<<<< HEAD
        
        stage('Generate App Key') {
            steps {
                echo 'Generating application key...'
                sh '''
                    php artisan key:generate --force
                '''
                echo 'Application key generated'
            }
        }
        
        stage('Build Assets') {
            steps {
                echo 'Building frontend assets...'
                sh '''
                    npm run build
                '''
                echo 'Assets compiled successfully'
            }
        }
        
=======

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

>>>>>>> origin/complet
        stage('Code Quality Checks') {
            parallel {
                stage('PHP Syntax Check') {
                    steps {
                        echo 'Checking PHP syntax...'
                        sh '''
<<<<<<< HEAD
                            find app -name "*.php" -exec php -l {} \\; | grep -v "No syntax errors"
=======
                            find app -name "*.php" -exec php -l {} \\; > /tmp/php-syntax-check.log 2>&1
                            if grep -q "Parse error" /tmp/php-syntax-check.log; then
                                cat /tmp/php-syntax-check.log
                                exit 1
                            fi
                            echo "All PHP files have valid syntax"
>>>>>>> origin/complet
                        '''
                        echo 'PHP syntax check passed'
                    }
                }
<<<<<<< HEAD
                
=======
>>>>>>> origin/complet
                stage('Code Style Check') {
                    steps {
                        echo 'Checking code style...'
                        script {
<<<<<<< HEAD
                            // Using PHP CS Fixer if available
=======
>>>>>>> origin/complet
                            def phpCsFixerExists = sh(
                                script: 'command -v php-cs-fixer',
                                returnStatus: true
                            ) == 0
<<<<<<< HEAD
                            
=======

>>>>>>> origin/complet
                            if (phpCsFixerExists) {
                                sh 'php-cs-fixer fix --dry-run --diff --verbose || true'
                            } else {
                                echo 'PHP CS Fixer not installed, skipping...'
                            }
                        }
                    }
                }
<<<<<<< HEAD
                
=======
>>>>>>> origin/complet
                stage('Security Audit') {
                    steps {
                        echo 'Running security audit...'
                        sh '''
<<<<<<< HEAD
                            # Composer security check
                            composer audit || true
                            
                            # NPM security audit
=======
                            composer audit || true
>>>>>>> origin/complet
                            npm audit --audit-level=moderate || true
                        '''
                    }
                }
            }
        }
<<<<<<< HEAD
        
        stage('Database Setup') {
            steps {
                echo 'Setting up test database...'
                
                script {
                    // Create test database with root user and empty password
                    sh '''
                        mysql -h${DB_HOST} -uroot -e "DROP DATABASE IF EXISTS ${DB_DATABASE};"
                        mysql -h${DB_HOST} -uroot -e "CREATE DATABASE ${DB_DATABASE};"
                    '''
                    
                    // Run migrations
                    sh '''
                        php artisan migrate --force --seed
                    '''
                    
=======

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
>>>>>>> origin/complet
                    echo 'Database setup completed'
                }
            }
        }
<<<<<<< HEAD
        
        stage('Run Tests') {
            steps {
                echo 'Running tests...'
                
                script {
                    sh '''
                        # Clear cache before testing
                        php artisan config:clear
                        php artisan cache:clear
                        
                        # Run PHPUnit tests
                        vendor/bin/phpunit --testdox --colors=always
                    '''
                }
                
                echo 'All tests passed'
            }
        }
        
=======

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

>>>>>>> origin/complet
        stage('Code Coverage') {
            when {
                expression { env.PHPUNIT_COVERAGE == 'true' }
            }
            steps {
                echo 'Generating code coverage report...'
<<<<<<< HEAD
                
=======
>>>>>>> origin/complet
                script {
                    sh '''
                        vendor/bin/phpunit --coverage-html coverage --coverage-clover coverage.xml || true
                    '''
                }
<<<<<<< HEAD
                
                // Publish coverage reports
=======
>>>>>>> origin/complet
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
<<<<<<< HEAD
        
        stage('Deploy to Staging') {
            when {
                allOf {
                    branch 'develop'
                    expression { 
                        return fileExists('/var/lib/jenkins/.ssh/deploy-ssh-key') || 
                               currentBuild.rawBuild.getEnvironment().containsKey('DEPLOY_SERVER')
                    }
                }
            }
            steps {
                echo 'Deploying to staging environment...'
                
                script {
                    try {
                        sshagent(['deploy-ssh-key']) {
                            sh '''
                                ssh -o StrictHostKeyChecking=no ${DEPLOY_SERVER} "
                                    cd ${DEPLOY_PATH}/staging &&
                                    git pull origin develop &&
                                    composer install --no-dev --optimize-autoloader &&
                                    npm ci --production &&
                                    npm run build &&
                                    php artisan migrate --force &&
                                    php artisan config:cache &&
                                    php artisan route:cache &&
                                    php artisan view:cache &&
                                    php artisan queue:restart &&
                                    sudo systemctl reload php8.2-fpm
                                "
                            '''
                        }
                        echo 'Deployed to staging successfully'
                    } catch (Exception e) {
                        echo "Deployment skipped: ${e.message}"
                        echo 'Configure deploy-ssh-key credential and DEPLOY_SERVER environment variable to enable deployment'
                    }
                }
            }
        }
        
        stage('Deploy to Production') {
            when {
                allOf {
                    branch 'main'
                    expression { 
                        return fileExists('/var/lib/jenkins/.ssh/deploy-ssh-key') || 
                               currentBuild.rawBuild.getEnvironment().containsKey('DEPLOY_SERVER')
                    }
                }
            }
            steps {
                input message: 'Deploy to Production?', ok: 'Deploy'
                
                echo 'Deploying to production environment...'
                
                script {
                    try {
                        sshagent(['deploy-ssh-key']) {
                            sh '''
                                ssh -o StrictHostKeyChecking=no ${DEPLOY_SERVER} "
                                    cd ${DEPLOY_PATH}/production &&
                                    
                                    # Backup current version
                                    php artisan down &&
                                    
                                    # Pull latest code
                                    git pull origin main &&
                                    
                                    # Install dependencies
                                    composer install --no-dev --optimize-autoloader &&
                                    npm ci --production &&
                                    npm run build &&
                                    
                                    # Run migrations
                                    php artisan migrate --force &&
                                    
                                    # Optimize
                                    php artisan config:cache &&
                                    php artisan route:cache &&
                                    php artisan view:cache &&
                                    
                                    # Clear old caches
                                    php artisan cache:clear &&
                                    
                                    # Restart services
                                    php artisan queue:restart &&
                                    sudo systemctl reload php8.2-fpm &&
                                    
                                    # Bring application back up
                                    php artisan up
                                "
                            '''
                        }
                        echo 'Deployed to production successfully'
                    } catch (Exception e) {
                        echo "Deployment skipped: ${e.message}"
                        echo 'Configure deploy-ssh-key credential and DEPLOY_SERVER environment variable to enable deployment'
                    }
                }
            }
        }
        
        stage('Cleanup') {
            steps {
                echo 'Cleaning up...'
                
                script {
                    // Clean up test database with root user and empty password
                    sh '''
                        mysql -h${DB_HOST} -uroot -e "DROP DATABASE IF EXISTS ${DB_DATABASE};" || true
                    '''
                    
                    // Clear Laravel caches
=======

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
>>>>>>> origin/complet
                    sh '''
                        php artisan cache:clear || true
                        php artisan config:clear || true
                        php artisan route:clear || true
                        php artisan view:clear || true
                    '''
                }
<<<<<<< HEAD
                
=======
>>>>>>> origin/complet
                echo 'Cleanup completed'
            }
        }
    }
<<<<<<< HEAD
    
=======

>>>>>>> origin/complet
    post {
        always {
            script {
                echo 'Pipeline execution completed'
                
<<<<<<< HEAD
                // Clean workspace only if we have a workspace context
=======
                // Arrêter les conteneurs Docker (commentez cette section si vous voulez les garder)
                sh '''
                    echo "Stopping Docker containers..."
                    docker stop atlas-mysql atlas-nexus 2>/dev/null || true
                    echo "Containers stopped (will restart on next build)"
                ''' 
                
>>>>>>> origin/complet
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
<<<<<<< HEAD
        
        success {
            script {
                echo 'Pipeline succeeded!'
                
                // Send success notification (configure as needed)
                try {
                    if (env.BRANCH_NAME == 'main') {
                        emailext(
                            subject: "Atlas-Roads: Production Deploy Success - Build #${BUILD_NUMBER}",
                            body: """
                                <h2>Production Deployment Successful!</h2>
                                <p><strong>Project:</strong> ${env.APP_NAME ?: 'atlas-roads'}</p>
                                <p><strong>Branch:</strong> ${env.BRANCH_NAME}</p>
                                <p><strong>Commit:</strong> ${env.GIT_COMMIT_SHORT ?: 'N/A'}</p>
                                <p><strong>Author:</strong> ${env.GIT_AUTHOR ?: 'N/A'}</p>
                                <p><strong>Message:</strong> ${env.GIT_COMMIT_MSG ?: 'N/A'}</p>
                                <p><strong>Build URL:</strong> ${BUILD_URL}</p>
                            """,
                            to: 'team@example.com',
                            mimeType: 'text/html'
                        )
                    }
                } catch (Exception e) {
                    echo "Email notification skipped: ${e.message}"
                }
            }
        }
        
        failure {
            script {
                echo 'Pipeline failed!'
                
                // Send failure notification
                try {
                    emailext(
                        subject: "Atlas-Roads: Build Failed - Build #${BUILD_NUMBER}",
                        body: """
                            <h2>Build Failed!</h2>
                            <p><strong>Project:</strong> ${env.APP_NAME ?: 'atlas-roads'}</p>
                            <p><strong>Branch:</strong> ${env.BRANCH_NAME ?: 'N/A'}</p>
                            <p><strong>Commit:</strong> ${env.GIT_COMMIT_SHORT ?: 'N/A'}</p>
                            <p><strong>Author:</strong> ${env.GIT_AUTHOR ?: 'N/A'}</p>
                            <p><strong>Message:</strong> ${env.GIT_COMMIT_MSG ?: 'N/A'}</p>
                            <p><strong>Build URL:</strong> ${BUILD_URL}</p>
                            <p><strong>Console:</strong> ${BUILD_URL}console</p>
                        """,
                        to: 'team@example.com',
                        mimeType: 'text/html'
                    )
                } catch (Exception e) {
                    echo "Email notification skipped: ${e.message}"
                }
            }
        }
        
        unstable {
            echo 'Pipeline is unstable'
        }
        
        changed {
            echo 'Pipeline status has changed'
        }
    }
}

=======
        // Notifs par mail (à adapter au besoin)
        // ...
    }
}
>>>>>>> origin/complet
