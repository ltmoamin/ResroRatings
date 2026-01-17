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
        sh "docker build -t ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER} ."
      }
    }
   
    stage("Push image") {
      steps {
        withCredentials([usernamePassword(credentialsId: "docker-hub-credentials", usernameVariable: "DOCKER_USER", passwordVariable: "DOCKER_PASS")]) {
          sh "echo $DOCKER_PASS | docker login ${REGISTRY} -u $DOCKER_USER --password-stdin"
          sh "docker push ${REGISTRY}/${IMAGE_NAME}:${BUILD_NUMBER}"
        }
      }
    }
  }
}
