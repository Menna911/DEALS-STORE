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
                        printf "MYSQL_ROOT_PASSWORD=%s\nMYSQL_DATABASE=%s\nMYSQL_USER=%s\nMYSQL_PASSWORD=%s\n" \
                        "$MYSQL_ROOT_PASSWORD" \
                        "$MYSQL_DATABASE" \
                        "$MYSQL_USER" \
                        "$MYSQL_PASSWORD" > .env


                        ls -l .env
                        '''
                    }
            }
        }

        stage('Validate Required Files'){
            steps{
                echo 'Validating required project files...'

                sh '''
                    [ -f docker-compose.yml ] || { echo "docker-compose.yml not found"; exit 1; }
                    [ -f backend/Dockerfile ] || { echo "backend Dockerfile not found"; exit 1; }
                    [ -f frontend/Dockerfile ] || { echo "frontend Dockerfile not found"; exit 1; }
                    [ -f Jenkinsfile ] || { echo "Jenkinsfile not found"; exit 1; }
                '''
            }
        }

        stage('Validate Docker Compose'){
            steps{
                echo 'Validating Docker Compose configuration...'

                sh '''
                docker compose -f $COMPOSE_FILE config
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
            sh 'rm -f .env'
            echo 'Pipeline execution finished.'
        }
    }
}