pipeline {
    agent any

    environment {
        REGISTRY = "docker.io"
        IMAGE_NAME = "moamina/resto_app"
    }

    stages {
        stage("Checkout") {
            steps {
                checkout scm
            }
        }

        stage("Build Docker Image") {
            steps {
                // Build avec tag basé sur le BUILD_NUMBER
                sh "docker build -t ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ."
            }
        }

        stage("Push Docker Image") {
            steps {
                // Utilise le credential Jenkins Username/Password
                withCredentials([usernamePassword(
                    credentialsId: 'docker-hub-credentials',
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_PASS'
                )]) {
                    // Login Docker Hub
                    sh 'echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin'

                    // Push avec tag unique
                    sh "docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}"

                    // Optionnel : mettre à jour le tag latest
                    sh "docker tag ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ${REGISTRY}/${IMAGE_NAME}:latest"
                    sh "docker push ${REGISTRY}/${IMAGE_NAME}:latest"

                    // Logout après push
                    sh "docker logout ${REGISTRY}"
                }
            }
        }
    }

    post {
        always {
            echo "Nettoyage Docker local..."
            sh "docker system prune -f --volumes"
        }
        success {
            echo "Pipeline terminé avec succès !"
        }
        failure {
            echo "Pipeline échoué."
        }
    }
}
