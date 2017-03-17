def containerName = "${BUILD_TAG}"

node {
	stage('Checkout') {
		checkout scm
		step([$class: 'GitHubSetCommitStatusBuilder'])
	}
	stage('Build') {
		try {
			sh "lxc launch ubuntu-daily:xenial ${containerName} -e"
			sh "lxc exec ${containerName} -- sh -c \"until grep -qF \\\"Cloud-init v. 0.7.9 finished at\\\" /var/log/cloud-init-output.log; do sleep 1; done\""
			sh "tar c . | lxc exec ${containerName} -- sh -c \"mkdir sixodp && tar -C sixodp -x\""
			sh "lxc exec ${containerName} -- sh -c \"cd sixodp/ansible && ansible-playbook -i inventories/build deploy-transitional.yml\""
			currentBuild.result = "SUCCESS"
		} catch (err) {
			currentBuild.result = "FAILURE"
			slackSend "Build Failed - ${env.JOB_NAME} ${env.BUILD_NUMBER} (<${env.BUILD_URL}|Open>)"
		} finally {
			sh "lxc stop ${containerName}"
			step([$class: 'GitHubCommitStatusSetter'])
		}
	}
}
