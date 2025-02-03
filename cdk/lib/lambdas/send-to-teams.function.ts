import {Handler} from "aws-cdk-lib/aws-lambda";
import * as https from 'https';

export const handler: Handler = async (event: any) => {
    const snsSubject = event["Records"][0]["Sns"]["Subject"]
    const snsMessage = JSON.parse(event["Records"][0]["Sns"]["Message"])

    // Format needs to be defined in teams workflow
    const teamsMessage = {
            "snsSubject": snsSubject + ", In environment: " + process.env.ENVIRONMENT,
            "snsNotificationType": snsMessage.notificationType,
            "snsTimestamp": snsMessage.mail.timestamp,
            "snsSource": snsMessage.mail.source,
            "snsDestination": snsMessage.mail.destination.join(","),
            "snsMessageId": snsMessage.mail.messageId
    }

    const postData = JSON.stringify(teamsMessage)

    const options: https.RequestOptions = {
        hostname: process.env.TEAMS_HOST,
        port: 443,
        path: process.env.TEAMS_PATH,
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'Content-Length': Buffer.byteLength(postData),
        }
    }

    await new Promise((resolve, reject) => {
        const req = https.request(options, (res: any) => {
            if (res.statusCode != 200) {
                console.log('Response from teams:', res.statusCode);
                res.on('data', (chunk: any) => {
                    console.log(chunk.toString());
                }).on('end', () => {
                    resolve(res);
                });
            } else {
                resolve(res);
            }
            })
            .on('error', (error: any) => {
                console.error('Error sending message to teams:', error);
                reject(error);
            });

        req.write(postData)
        req.end()
        })



    return {
        statusCode: 200,
        body: "message handled"
    }
}