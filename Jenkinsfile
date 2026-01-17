pipeline {
    agent any

    environment {
        REGISTRY = "docker.io"
        IMAGE_NAME = "moamina/resto_app"
    }

    stages {
        stage("Checkout") {
            steps {
                echo "Récupération du code source..."
                checkout scm
            }
        }

        stage("Build Docker Image") {
            steps {
                echo "Construction de l'image Docker..."
                sh """
                    docker build -t ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} .
                """
            }
        }

        stage("Push Docker Image") {
            steps {
                echo "Push vers Docker Hub..."
                withCredentials([usernamePassword(
                    credentialsId: 'docker-hub-credentials', // Jenkins credential ID
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_TOKEN'
                )]) {
                    sh """
                        # Login Docker avec token
                        echo $DOCKER_TOKEN | docker login -u $DOCKER_USER --password-stdin

                        # Push avec tag BUILD_NUMBER
                        docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}

                        # Tag et push latest
                        docker tag ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ${REGISTRY}/${IMAGE_NAME}:latest
                        docker push ${REGISTRY}/${IMAGE_NAME}:latest

                        # Logout
                        docker logout ${REGISTRY}
                    """
                }
            }
        }
    }

    post {
        always {
            echo "Nettoyage des images et volumes Docker locaux..."
            sh "docker system prune -f --volumes"
        }
        success {
            echo "Pipeline terminé avec succès ! 🎉"
        }
        failure {
            echo "Pipeline échoué. ❌"
        }
    }
}
