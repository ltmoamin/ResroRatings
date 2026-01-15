pipeline {
    agent any

    stages {
        stage('Build') {
            steps {
                script {
                    if (isUnix()) {
                        sh 'composer install'
                    } else {
                        bat 'composer install'
                    }
                }
            }
        }
        stage('Test') {
            steps {
                script {
                    if (isUnix()) {
                        sh 'php bin/phpunit'
                    } else {
                        bat 'php bin/phpunit'
                    }
                }
            }
        }
        stage('Deploy') {
            steps {
                script {
                    if (isUnix()) {
                        sh 'docker-compose down'
                        sh 'docker-compose up -d --build'
                    } else {
                        bat 'docker-compose down'
                        bat 'docker-compose up -d --build'
                    }
                }
            }
        }
    }
}
