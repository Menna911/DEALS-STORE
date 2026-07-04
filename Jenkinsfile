pipeline {
    agent any

    stages {

        stage('Prepare Environment') {
            steps {
                bat 'copy .env.example .env'
            }
        }

        stage('Build Docker Images') {
            steps {
                bat 'docker compose build'
            }
        }

        stage('Run Containers') {
            steps {
                bat 'docker compose up -d --wait'
            }
        }

        stage('Show Running Containers') {
            steps {
                bat 'docker ps'
            }
        }
    }
}