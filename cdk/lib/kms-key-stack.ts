import {aws_kms, Stack, StackProps} from "aws-cdk-lib";
import {Construct} from "constructs";

export class KmsKeyStack extends Stack {
    constructor(scope: Construct, id: string, props?: StackProps) {
        super(scope, id, props);

        new aws_kms.Key(this, 'databaseEncryptionKey', {
            alias: 'database-encryption-key'
        })

        new aws_kms.Key(this, 'secretEncryptionKey', {
            alias: 'secret-encryption-key'
        })

    }

}