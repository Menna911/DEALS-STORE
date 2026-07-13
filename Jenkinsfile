pipeline {
    agent any

    options {
        timestamps()
        disableConcurrentBuilds()
    }

    environment {
        COMPOSE_FILE = 'docker-compose.yml'
        DOCKERHUB_USERNAME = 'moaaz65'
        IMAGE_TAG = "${BUILD_NUMBER}"
        IMAGE_LATEST = "latest"
        FRONTEND_IMAGE = "${DOCKERHUB_USERNAME}/deals-store-frontend"
        BACKEND_IMAGE = "${DOCKERHUB_USERNAME}/deals-store-backend"
        MYSQL_IMAGE = "${DOCKERHUB_USERNAME}/deals-store-mysql"
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

        stage('Login to Docker Hub') {
            steps {
                echo 'Logging in to Docker Hub...'

                withCredentials([
                    usernamePassword(
                    credentialsId: 'Docker_Access_Token',
                    usernameVariable: 'DOCKER_USERNAME',
                    passwordVariable: 'DOCKER_TOKEN'
                )
                ]){
                sh '''
                    echo "$DOCKER_TOKEN" | docker login \
                        --username "$DOCKER_USERNAME" \
                        --password-stdin
                '''
                }
            }
        }

        stage('Tag Docker Images'){
            steps{
                echo 'Tagging Docker images...'

                sh '''
                docker tag ${FRONTEND_IMAGE}:${IMAGE_TAG} \
                       ${FRONTEND_IMAGE}:${IMAGE_LATEST}

                docker tag ${BACKEND_IMAGE}:${IMAGE_TAG} \
                       ${BACKEND_IMAGE}:${IMAGE_LATEST}

                docker tag ${MYSQL_IMAGE}:${IMAGE_TAG} \
                       ${MYSQL_IMAGE}:${IMAGE_LATEST}
                '''
            }
        }

        stage('Push Docker Images') {
            steps {
                echo 'Pushing Docker images to Docker Hub...'

                sh '''
                docker push ${FRONTEND_IMAGE}:${IMAGE_TAG}
                docker push ${FRONTEND_IMAGE}:${IMAGE_LATEST}

                docker push ${BACKEND_IMAGE}:${IMAGE_TAG}
                docker push ${BACKEND_IMAGE}:${IMAGE_LATEST}

                docker push ${MYSQL_IMAGE}:${IMAGE_TAG}
                docker push ${MYSQL_IMAGE}:${IMAGE_LATEST}
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

        stage('Application Health Check') {
            steps {
                echo 'Checking application health...'

                sh '''
                    docker compose -f $COMPOSE_FILE ps
                    docker compose -f $COMPOSE_FILE exec -T backend \
                    curl --fail --silent --show-error \
                    http://localhost:8000/api/offers.php
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
        sh '''
            docker logout || true

            rm -f .env
        '''
        echo 'Pipeline execution finished.'
    }
}
    }
}