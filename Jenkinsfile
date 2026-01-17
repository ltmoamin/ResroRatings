pipeline {
    agent any

    environment {
        REGISTRY = "docker.io"
        IMAGE_NAME = "moamina/resto_app"
        COMPOSE_FILE = "docker-compose.yml"
        MAX_PUSH_RETRIES = 3
    }

    stages {
        stage("Checkout") {
            steps {
                echo "Récupération du code source..."
                checkout scm
            }
        }

        stage("Start DB for Build") {
            steps {
                echo "Démarrage du container DB pour que l'app puisse se build correctement..."
                sh """
                    docker-compose -f ${COMPOSE_FILE} up -d db
                    # Attendre que MySQL soit prêt
                    echo "Attente de la disponibilité de MySQL..."
                    for i in {1..30}; do
                        docker exec resto_db mysqladmin ping -uroot -proot &>/dev/null && break
                        echo "MySQL non prêt, attente 2s..."
                        sleep 2
                    done
                """
            }
        }

        stage("Build App Image") {
            steps {
                echo "Construction de l'image de l'application..."
                sh """
                    docker-compose -f ${COMPOSE_FILE} build app
                """
            }
        }

        stage("Tag App Image") {
            steps {
                echo "Tagging de l'image pour Docker Hub..."
                sh """
                    docker tag resto_app:latest ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}
                    docker tag resto_app:latest ${REGISTRY}/${IMAGE_NAME}:latest
                """
            }
        }

        stage("Push App Image") {
            steps {
                echo "Push vers Docker Hub avec retry automatique..."
                withCredentials([usernamePassword(
                    credentialsId: 'docker-hub-credentials',
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_TOKEN'
                )]) {
                    script {
                        def retries = 0
                        def success = false
                        while (!success && retries < MAX_PUSH_RETRIES) {
                            try {
                                sh """
                                    echo $DOCKER_TOKEN | docker login -u $DOCKER_USER --password-stdin
                                    docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}
                                    docker push ${REGISTRY}/${IMAGE_NAME}:latest
                                    docker logout ${REGISTRY}
                                """
                                success = true
                                echo "Push réussi ✅"
                            } catch (Exception e) {
                                retries++
                                echo "Push échoué, tentative ${retries} / ${MAX_PUSH_RETRIES}"
                                if (retries >= MAX_PUSH_RETRIES) {
                                    error("Impossible de push l'image Docker après ${MAX_PUSH_RETRIES} tentatives.")
                                }
                                echo "Nouvelle tentative dans 10 secondes..."
                                sleep 10
                            }
                        }
                    }
                }
            }
        }
    }

    post {
        always {
            echo "Nettoyage complet des containers et images Docker..."
            sh """
                docker-compose -f ${COMPOSE_FILE} down --volumes --remove-orphans
                docker system prune -af --volumes
            """
        }
        success {
            echo "Pipeline terminé avec succès ! 🎉"
        }
        failure {
            echo "Pipeline échoué. ❌"
        }
    }
}
