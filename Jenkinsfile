pipeline {
    agent any

    stages {

        stage('Prepare Environment') {
            steps {
                sh '''
                    pwd
                    ls -la
                    cp .env.example .env
                '''
            }
        }

        stage('Build Docker Images') {
            steps {
                sh '''
                    docker compose build
                '''
            }
        }

        stage('Run Containers') {
            steps {
                sh '''
                    docker compose up -d 
                '''
            }
        }

        stage('Show Running Containers') {
            steps {
                sh '''
                    docker ps
                    curl -f http://localhost || exit 1
                    curl -f http://localhost:8000/api/offers.php || exit 1
                '''
            }
        }
    }
}