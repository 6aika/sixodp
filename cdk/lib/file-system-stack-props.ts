import {NetworkStackProps} from "./network-stack-props";

export interface FileSystemStackProps extends NetworkStackProps {
    migrationFileSystemId?: string
    migrationFileSystemSecurityGroupId?: string
}