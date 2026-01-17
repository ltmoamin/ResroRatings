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

        stage("Build image") {
            steps {
                // Build l'image Docker avec le tag BUILD_NUMBER
                sh "docker build -t ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ."
            }
        }

        stage("Push image") {
            steps {
                // Utilise le credential Username/Password directement
                withCredentials([usernamePassword(
                    credentialsId: 'docker-hub-credentials', 
                    usernameVariable: 'DOCKER_USER', 
                    passwordVariable: 'DOCKER_PASS'
                )]) {
                    // Login Docker Hub
                    sh 'echo $DOCKER_PASS | docker login -u $DOCKER_USER --password-stdin'
                    
                    // Retry 3 fois pour éviter les erreurs réseau / blobs
                    retry(3) {
                        // Push de l'image avec le tag BUILD_NUMBER
                        sh "docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}"
                    }

                    // Tag et push la version latest séparément
                    sh "docker tag ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ${REGISTRY}/${IMAGE_NAME}:latest"
                    retry(3) {
                        sh "docker push ${REGISTRY}/${IMAGE_NAME}:latest"
                    }
                }
            }
        }
    }

    post {
        always {
            // Logout Docker après le push
            sh "docker logout ${REGISTRY}"
        }
    }
}
