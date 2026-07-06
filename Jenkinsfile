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
                
                sh '''
                    // pwd
                    // ls -la
                    cp .env.example .env
                '''
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
            echo 'Pipeline execution finished.'
        }

    }
}