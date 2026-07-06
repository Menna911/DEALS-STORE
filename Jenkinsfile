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
                    docker compose up -d --wait
                '''
            }
        }

        stage('Show Running Containers') {
            steps {
                sh '''
                    docker ps
                '''
            }
        }
    }
}