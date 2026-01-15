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
    }
}
