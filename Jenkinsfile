pipeline {
    agent any

    stages {

        stage('Checkout') {
            steps {
                git branch: 'devops', url: 'https://github.com/Moaazkaff/DEALS-STORE.git'
            }
        }

        stage('Build Docker Images') {
            steps {
                bat 'docker compose build'
            }
        }

        stage('Run Containers') {
            steps {
                bat 'docker compose up -d'
            }
        }

        stage('Show Running Containers') {
            steps {
                bat 'docker ps'
            }
        }
    }
}