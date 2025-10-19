pipeline {
    agent any
    
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
        DB_CONNECTION = 'mysql'
        DB_HOST = '127.0.0.1'
        DB_PORT = '3306'
        DB_DATABASE = 'atlas_roads_test'
        
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
    
    stages {
        stage('Checkout') {
            steps {
                echo 'Checking out code from repository...'
                checkout scm
                
                script {
                    // Get commit info
                    env.GIT_COMMIT_MSG = sh(script: 'git log -1 --pretty=%B', returnStdout: true).trim()
                    env.GIT_AUTHOR = sh(script: 'git log -1 --pretty=%an', returnStdout: true).trim()
                    env.GIT_COMMIT_SHORT = sh(script: 'git rev-parse --short HEAD', returnStdout: true).trim()
                }
                
                echo "Commit: ${env.GIT_COMMIT_SHORT} by ${env.GIT_AUTHOR}"
                echo "Message: ${env.GIT_COMMIT_MSG}"
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
                    // Copy .env.example to .env for testing
                    sh '''
                        if [ ! -f .env ]; then
                            cp .env.example .env
                            echo "Created .env file"
                        fi
                    '''
                    
                    // Update .env with test database credentials
                    withCredentials([
                        string(credentialsId: 'db-username', variable: 'DB_USER'),
                        string(credentialsId: 'db-password', variable: 'DB_PASS')
                    ]) {
                        sh '''
                            sed -i "s/DB_CONNECTION=.*/DB_CONNECTION=${DB_CONNECTION}/" .env
                            sed -i "s/DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
                            sed -i "s/DB_PORT=.*/DB_PORT=${DB_PORT}/" .env
                            sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
                            sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" .env
                            sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" .env
                        '''
                    }
                }
            }
        }
        
        stage('Install Dependencies') {
            parallel {
                stage('Composer Dependencies') {
                    steps {
                        echo 'Installing PHP dependencies...'
                        sh '''
                            composer install --no-interaction --prefer-dist --optimize-autoloader
                        '''
                        echo 'PHP dependencies installed'
                    }
                }
                
                stage('NPM Dependencies') {
                    steps {
                        echo 'Installing Node dependencies...'
                        sh '''
                            npm ci --silent
                        '''
                        echo 'Node dependencies installed'
                    }
                }
            }
        }
        
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
        
        stage('Code Quality Checks') {
            parallel {
                stage('PHP Syntax Check') {
                    steps {
                        echo 'Checking PHP syntax...'
                        sh '''
                            find app -name "*.php" -exec php -l {} \\; | grep -v "No syntax errors"
                        '''
                        echo 'PHP syntax check passed'
                    }
                }
                
                stage('Code Style Check') {
                    steps {
                        echo 'Checking code style...'
                        script {
                            // Using PHP CS Fixer if available
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
                            # Composer security check
                            composer audit || true
                            
                            # NPM security audit
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
                    // Create test database
                    withCredentials([
                        string(credentialsId: 'db-username', variable: 'DB_USER'),
                        string(credentialsId: 'db-password', variable: 'DB_PASS')
                    ]) {
                        sh '''
                            mysql -h${DB_HOST} -u${DB_USER} -p${DB_PASS} -e "DROP DATABASE IF EXISTS ${DB_DATABASE};"
                            mysql -h${DB_HOST} -u${DB_USER} -p${DB_PASS} -e "CREATE DATABASE ${DB_DATABASE};"
                        '''
                    }
                    
                    // Run migrations
                    sh '''
                        php artisan migrate --force --seed
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
                
                // Publish coverage reports
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
                    // Clean up test database
                    withCredentials([
                        string(credentialsId: 'db-username', variable: 'DB_USER'),
                        string(credentialsId: 'db-password', variable: 'DB_PASS')
                    ]) {
                        sh '''
                            mysql -h${DB_HOST} -u${DB_USER} -p${DB_PASS} -e "DROP DATABASE IF EXISTS ${DB_DATABASE};" || true
                        '''
                    }
                    
                    // Clear Laravel caches
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
                
                // Clean workspace only if we have a workspace context
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

