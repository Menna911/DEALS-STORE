pipeline {
    agent any

    options {
        timestamps()
        disableConcurrentBuilds()
    }

    environment {
        COMPOSE_FILE = 'docker-compose.yml'
    }

    stages {

        stage('Checkout Source Code') {
            steps {
                checkout scm
            }
        }

        stage('Prepare Environment') {
            steps {
                echo 'Preparing environment...'

                withCredentials([
                    string(credentialsId: 'MYSQL_ROOT_PASSWORD', variable: 'MYSQL_ROOT_PASSWORD'),
                    string(credentialsId: 'MYSQL_DATABASE', variable: 'MYSQL_DATABASE'),
                    string(credentialsId: 'MYSQL_USER', variable: 'MYSQL_USER'),
                    string(credentialsId: 'MYSQL_PASSWORD', variable: 'MYSQL_PASSWORD')
                ]) {
                        sh '''
                        cat > .env <<EOF
                        MYSQL_ROOT_PASSWORD=$MYSQL_ROOT_PASSWORD
                        MYSQL_DATABASE=$MYSQL_DATABASE
                        MYSQL_USER=$MYSQL_USER
                        MYSQL_PASSWORD=$MYSQL_PASSWORD
                        EOF

                        ls -l .env
                        '''
                    }
            }
        }

        stage('Build Application Images') {
            steps {
                echo 'Building Docker images...'

                sh '''
                    docker compose -f $COMPOSE_FILE build
                '''
            }
        }

        stage('Start Application Stack') {
            steps {
                echo 'Starting application stack...'

                sh '''
                    docker compose -f $COMPOSE_FILE up -d --wait
                '''
            }
        }

        stage('Verify Running Containers') {
            steps {
                echo 'Verifying running containers...'

                sh '''
                    docker compose -f $COMPOSE_FILE ps
                '''
            }
        }
    }

    post {
        success {
            echo 'Build completed successfully.'
        }
    
        failure {
            echo 'Build failed.'
        }
    
        always {
            sh 'rm -f .env'
            echo 'Pipeline execution finished.'
        }
    }
}