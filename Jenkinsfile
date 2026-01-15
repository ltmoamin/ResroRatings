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
        stage('Build Image') {
            steps {
                script {
                    if (isUnix()) {
                        sh 'docker-compose build --no-cache'
                    } else {
                        bat 'docker-compose build --no-cache'
                    }
                }
            }
        }
        stage('Push Image') {
            steps {
                script {
                    withCredentials([usernamePassword(credentialsId: 'docker-hub-credentials', passwordVariable: 'DOCKER_PASSWORD', usernameVariable: 'DOCKER_USERNAME')]) {
                        if (isUnix()) {
                            sh 'docker login -u $DOCKER_USERNAME -p $DOCKER_PASSWORD'
                            sh 'docker-compose push'
                        } else {
                            bat 'docker login -u %DOCKER_USERNAME% -p %DOCKER_PASSWORD%'
                            bat 'docker-compose push'
                        }
                    }
                }
            }
        }
        stage('Deploy') {
            steps {
                script {
                    if (isUnix()) {
                        sh 'docker-compose down'
                        sh 'docker-compose up -d'
                    } else {
                        bat 'docker-compose down'
                        bat 'docker-compose up -d'
                    }
                }
            }
        }
    }
}
