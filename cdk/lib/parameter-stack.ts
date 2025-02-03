import {aws_ssm, Stack, StackProps,} from "aws-cdk-lib";
import {Construct} from "constructs";


export class ParameterStack extends Stack {

    readonly ckanDatabaseSnapshotParameterName: string
    readonly wpDatabaseSnapshotParameterName: string
    readonly pgAdminAllowedPrefix: string
    readonly teamsHostnameParameterName: string
    readonly teamsWorkflowPathParameterName: string

    constructor(scope: Construct, id: string, props: StackProps) {
        super(scope, id, props);

        this.ckanDatabaseSnapshotParameterName = '/rds/ckan_database_snapshot_identifier';
        new aws_ssm.StringParameter(this, 'ckanDatabaseSnapshot', {
            stringValue: 'some placeholder',
            description: 'Snapshot identifier of ckan rds database',
            parameterName: this.ckanDatabaseSnapshotParameterName,
        })

        this.wpDatabaseSnapshotParameterName = '/rds/wordpress_database_snapshot_identifier';
        new aws_ssm.StringParameter(this, 'wpDatabaseSnapshot', {
            stringValue: 'some placeholder',
            description: 'Snapshot identifier of wp rds database',
            parameterName: this.wpDatabaseSnapshotParameterName,
        })

        this.pgAdminAllowedPrefix = '/elb/pgAdminAllowedIp';

        new aws_ssm.StringParameter(this, 'pgAdminAllowedIp1', {
            stringValue: 'some placeholder',
            description: 'Allowed ip 1 in pgadmin',
            parameterName: `${this.pgAdminAllowedPrefix}1`
        })

        new aws_ssm.StringParameter(this, 'pgAdminAllowedIp2', {
            stringValue: 'some placeholder',
            description: 'Allowed ip 2 in pgadmin',
            parameterName: `${this.pgAdminAllowedPrefix}2`
        })


        new aws_ssm.StringParameter(this, 'pgAdminAllowedIp3', {
            stringValue: 'some placeholder',
            description: 'Allowed ip 3 in pgadmin',
            parameterName: `${this.pgAdminAllowedPrefix}3`
        })

        new aws_ssm.StringParameter(this, 'pgAdminAllowedIp4', {
            stringValue: 'some placeholder',
            description: 'Allowed ip 4 in pgadmin',
            parameterName: `${this.pgAdminAllowedPrefix}4`
        })


        this.teamsHostnameParameterName = '/ses/teamsHostname';

        new aws_ssm.StringParameter(this,'snsTeamsHost', {
            stringValue: 'some placeholder',
            description: 'Teams workflow hostname',
            parameterName: this.teamsHostnameParameterName
        })

        this.teamsWorkflowPathParameterName = '/ses/teamsPath';

        new aws_ssm.StringParameter(this,'snsTeamsPath', {
            stringValue: 'some placeholder',
            description: 'Teams workflow path',
            parameterName: this.teamsWorkflowPathParameterName
        })
    }
}