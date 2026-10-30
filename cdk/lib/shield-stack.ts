import {
    aws_cloudwatch,
    aws_route53,
    aws_shield,
    aws_ssm,
    aws_wafv2,
    CfnParameter,
    Stack,
    StackProps, Token
} from "aws-cdk-lib";
import {Construct} from "constructs";
import {ShieldStackProps} from "./shield-stack-props";

import { z } from "zod";

export class ShieldStack extends Stack {
    constructor(scope: Construct, id: string, props: ShieldStackProps) {
        super(scope, id, props);

        const banned_ips = new CfnParameter(this, 'bannedIpsList', {
            type: 'AWS::SSM::Parameter::Value<List<String>>',
            default: props.bannedIpListParameterName
        })

        const cfnBannedIPSet = new aws_wafv2.CfnIPSet(this, 'BannedIPSet', {
            name: 'banned-ips',
            scope: 'REGIONAL',
            ipAddressVersion: "IPV4",
            addresses: banned_ips.valueAsList
        })

        const whitelisted_ips = new CfnParameter(this, 'whitelistedIpsList', {
            type: 'AWS::SSM::Parameter::Value<List<String>>',
            default: props.whitelistedIpListParameterName
        })

        const cfnWhiteListedIpSet = new aws_wafv2.CfnIPSet(this, 'WhitelistedIPSet', {
            name: 'whitelisted-ips',
            scope: 'REGIONAL',
            ipAddressVersion: "IPV4",
            addresses: whitelisted_ips.valueAsList
        })




        let rules: aws_wafv2.CfnWebACL.RuleProperty[] = [
            {
                name: 'block-banned_ips',
                priority: 0,
                action: {
                    block: {}
                },
                statement: {
                    ipSetReferenceStatement: {
                        arn: cfnBannedIPSet.attrArn
                    }
                },
                visibilityConfig: {
                    cloudWatchMetricsEnabled: true,
                    metricName: "banned-ips",
                    sampledRequestsEnabled: false
                }
            },
            {
                name: 'allow-whitelisted_ips',
                priority: 1,
                action: {
                    allow:{}
                },
                statement: {
                    ipSetReferenceStatement: {
                        arn: cfnWhiteListedIpSet.attrArn
                    }
                },
                visibilityConfig: {
                    cloudWatchMetricsEnabled: true,
                    metricName: "whitelisted-ips",
                    sampledRequestsEnabled: false
                }
            }
        ]

        if (props.onlyAllowWhitelistedCountries) {
            const whitelistedCountryCodesParameter = new CfnParameter(this,  'whitelistedCountryCodesParameter', {
                type: 'AWS::SSM::Parameter::Value<List<String>>',
                default: props.whitelistedCountriesParameterName
            });

            const whitelistedCountries: aws_wafv2.CfnWebACL.RuleProperty = {
                name: "whitelisted-countries",
                priority: rules.length,
                action: {
                    block: {}
                },
                statement: {
                    notStatement: {
                        statement: {
                            geoMatchStatement: {
                                countryCodes: whitelistedCountryCodesParameter.valueAsList
                            }
                        }
                    }
                },
                visibilityConfig: {
                    cloudWatchMetricsEnabled: true,
                    metricName: "request-whitelisted-countries",
                    sampledRequestsEnabled: true
                }

            }

            rules.push(whitelistedCountries)
        }

        if (props.blockBlacklistedCountries) {
            const blacklistedCountryCodesParameter = new CfnParameter(this,  'blacklistedCountryCodesParameter', {
                type: 'AWS::SSM::Parameter::Value<List<String>>',
                default: props.blacklistedCountriesParameterName
            });

            const blacklistedCountries: aws_wafv2.CfnWebACL.RuleProperty = {
                name: "blacklisted-countries",
                priority: rules.length,
                action: {
                    block: {}
                },
                statement: {
                    geoMatchStatement: {
                        countryCodes: blacklistedCountryCodesParameter.valueAsList
                    }
                },
                visibilityConfig: {
                    cloudWatchMetricsEnabled: true,
                    metricName: "request-blacklisted-countries",
                    sampledRequestsEnabled: true
                }

            }

            rules.push(blacklistedCountries)
        }


        const RateLimitASNsGroupSchema = z.array(
            z.number()
        )

        const rateLimitedASNsParameter = aws_ssm.StringParameter.valueFromLookup(this, props.rateLimitedASNsParameterName)
        const rateLimitedASNsJson = rateLimitedASNsParameter.startsWith("dummy-value") ? "dummy" : JSON.parse(rateLimitedASNsParameter)

        if ( rateLimitedASNsJson !== "dummy") {

            const rateLimitedASNs = RateLimitASNsGroupSchema.parse(rateLimitedASNsJson)
            if (rateLimitedASNs.length > 0) {
                const limitASNRule: aws_wafv2.CfnWebACL.RuleProperty = {
                    name: 'rate-limited-ASNs',
                    priority: rules.length,
                    action: {
                        block: {}
                    },
                    statement: {
                        rateBasedStatement: {
                            limit: 10,
                            aggregateKeyType: "CONSTANT",
                            evaluationWindowSec: 60,
                            scopeDownStatement: {
                                asnMatchStatement: {
                                    asnList: rateLimitedASNs
                                }
                            }
                        }
                    },
                    visibilityConfig: {
                        cloudWatchMetricsEnabled: true,
                        metricName: "rate-limited-ASNs",
                        sampledRequestsEnabled: true
                    }
                }
                rules.push(limitASNRule)

                if (props.blockASNs) {
                    const blockASNRule: aws_wafv2.CfnWebACL.RuleProperty = {
                        name: 'blocked-ASNs',
                        priority: rules.length,
                        action: {
                            block: {}
                        },
                        statement: {
                            asnMatchStatement: {
                                asnList: rateLimitedASNs
                            }
                        },
                        visibilityConfig: {
                            cloudWatchMetricsEnabled: true,
                            metricName: "blocked-ASNs",
                            sampledRequestsEnabled: true
                        }
                    }
                    rules.push(blockASNRule)
                }
            }
        }


        if (props.limitCountries) {
            const rateLimitedCountryCodesParameter = new CfnParameter(this,  'rateLimitedCountryCodesParameter', {
                type: 'AWS::SSM::Parameter::Value<List<String>>',
                default: props.rateLimitedCountriesParameterName
            });

            const rateLimitedCountries: aws_wafv2.CfnWebACL.RuleProperty = {
                name: "rate-limit-countries",
                priority: rules.length,
                action: {
                    block: {}
                },
                statement: {
                    rateBasedStatement: {
                        limit: 10,
                        aggregateKeyType: "CONSTANT",
                        evaluationWindowSec: 60,
                        scopeDownStatement: {
                            geoMatchStatement: {
                                countryCodes: rateLimitedCountryCodesParameter.valueAsList
                            }
                        }
                    }
                },
                visibilityConfig: {
                    cloudWatchMetricsEnabled: true,
                    metricName: "request-rate-limit-countries",
                    sampledRequestsEnabled: true
                }

            }

            rules.push(rateLimitedCountries)
        }


        const mediumPriorityCountryCodesParameter = new CfnParameter(this,  'mediumPriorityCountryCodesParameter', {
            type: 'AWS::SSM::Parameter::Value<List<String>>',
            default: props.mediumPriorityCountryCodeListParameterName
        });

        const rateLimitedPathsParameter = aws_ssm.StringParameter.valueFromLookup(this, props.rateLimitedPathsParameterName, '[]')
        const rateLimitedPathsJson = JSON.parse(rateLimitedPathsParameter)
        const rateLimitedPathsSchema = z.array(
            z.object({
                    limit: z.number(),
                    evaluationWindowSec: z.number(),
                    regexPath: z.string(),
                    name: z.string()
                }
            ))

        let rateLimitedPathsRules: any[] = []
        const validatedPaths = rateLimitedPathsSchema.parse(rateLimitedPathsJson)

        validatedPaths.forEach((rule, index: number) => {
            let rateLimitedPathRule: aws_wafv2.CfnWebACL.RuleProperty = {
                statement: {
                    rateBasedStatement: {
                        limit: rule.limit,
                        evaluationWindowSec: rule.evaluationWindowSec,
                        aggregateKeyType: "CUSTOM_KEYS",
                        customKeys: [
                            {
                                asn: {}
                            }
                        ],
                        scopeDownStatement: {
                            andStatement: {
                                statements: [
                                    {
                                        regexMatchStatement: {
                                            fieldToMatch: {
                                                uriPath: {}
                                            },
                                            regexString: rule.regexPath,
                                            textTransformations: [{
                                                type: "NONE",
                                                priority: 0
                                            }]
                                        }
                                    },
                                    {
                                        sizeConstraintStatement: {
                                            fieldToMatch: {
                                                queryString: {}
                                            },
                                            comparisonOperator: "GE",
                                            size: 1,
                                            textTransformations: [{
                                                type: "NONE",
                                                priority: 0
                                            }]
                                        }
                                    },
                                    {
                                        notStatement: {
                                            statement: {
                                                geoMatchStatement: {
                                                    countryCodes: mediumPriorityCountryCodesParameter.valueAsList
                                                }
                                            }
                                        }
                                    }
                                ]
                            }
                        }
                    }
                },
                action: {
                    block: {}
                },
                name: `rate-limited-paths-${rule.name}`,
                priority: rules.length + index,
                visibilityConfig: {
                    cloudWatchMetricsEnabled: true,
                    metricName: `rate-limited-paths-${rule.name}`,
                    sampledRequestsEnabled: true
                }
            }

            rateLimitedPathsRules.push(rateLimitedPathRule)
        })

        rules = rules.concat(rateLimitedPathsRules)


        const RuleGroupSchema = z.array(
            z.object(
                {
                    groupName: z.string(),
                    vendorName: z.string(),
                    ruleActionOverrideCounts: z.array(z.string()).default([]),
                    ruleActionOverrideBlocks: z.array(z.string()).default([])
                }
            ).strict()
        )

        const managedRulesParameter = aws_ssm.StringParameter.valueFromLookup(this, props.managedRulesParameterName)
        const managedRules = managedRulesParameter.startsWith("dummy-value") ? "dummy" : JSON.parse(managedRulesParameter)

        if ( managedRules !== "dummy"){
            let ruleList: any[] = []
            const validatedRules = RuleGroupSchema.parse(managedRules)
            validatedRules.forEach((rule, index: number) => {

                let ruleActionOverrides = []

                for (let overrideCountRule of rule.ruleActionOverrideCounts) {
                    let overrideCountRuleObj = {
                        actionToUse: {
                            count: {}
                        },
                        name: overrideCountRule
                    }

                    ruleActionOverrides.push(overrideCountRuleObj)
                }

                for (let overrideBlockRule of rule.ruleActionOverrideBlocks) {
                    let overrideBlockRuleObj = {
                        actionToUse: {
                            block: {}
                        },
                        name: overrideBlockRule
                    }

                    ruleActionOverrides.push(overrideBlockRuleObj)
                }

                let managedRuleGroup: aws_wafv2.CfnWebACL.RuleProperty = {
                    name: "managed-rule-group-" + rule.groupName,
                    priority: rules.length + index,
                    overrideAction: {
                        none: {}
                    },
                    statement: {
                        managedRuleGroupStatement: {
                            name: rule.groupName,
                            vendorName: rule.vendorName,
                            ruleActionOverrides: ruleActionOverrides
                        }
                    },
                    visibilityConfig: {
                        cloudWatchMetricsEnabled: true,
                        metricName: "request-managed-rule-group-" + rule.groupName,
                        sampledRequestsEnabled: true
                    }
                }


                ruleList.push(managedRuleGroup)
            })
            rules = rules.concat(ruleList)
        }


        const rateLimitNonBotTrafficRule: aws_wafv2.CfnWebACL.RuleProperty = {
            name: 'ratelimit-nonbot-traffic',
            priority: rules.length,
            action: {
                block: {}
            },
            statement: {
                rateBasedStatement: {
                    limit: 10,
                    evaluationWindowSec: 60,
                    aggregateKeyType: "CONSTANT",
                    scopeDownStatement: {
                        andStatement: {
                            statements: [
                                {
                                    notStatement: {
                                        statement: {
                                            orStatement: {
                                                statements: [
                                                    {
                                                        labelMatchStatement: {
                                                            scope: "LABEL",
                                                            key: "awswaf:managed:aws:bot-control:bot:verified"
                                                        }
                                                    },
                                                    {
                                                        labelMatchStatement: {
                                                            scope: "LABEL",
                                                            key: "awswaf:managed:aws:bot-control:bot:unverified"
                                                        }
                                                    },
                                                    {
                                                        labelMatchStatement: {
                                                            scope: "LABEL",
                                                            key: "awswaf:managed:aws:bot-control:bot:developer_platform:verified"
                                                        }
                                                    },
                                                    {
                                                        labelMatchStatement: {
                                                            scope: "LABEL",
                                                            key: "awswaf:managed:aws:bot-control:bot:user_triggered:verified"
                                                        }
                                                    }
                                                ]
                                            }
                                        }
                                    }
                                },
                                {
                                    notStatement: {
                                        statement: {
                                            geoMatchStatement: {
                                                countryCodes: mediumPriorityCountryCodesParameter.valueAsList
                                            }
                                        }
                                    }
                                }
                            ]
                        },
                    },
                }
            },
            visibilityConfig: {
                cloudWatchMetricsEnabled: true,
                metricName: 'ratelimit-nonbot-traffic',
                sampledRequestsEnabled: true
            }
        }

        const nonBotRules: any[] = [rateLimitNonBotTrafficRule]

        rules = rules.concat(nonBotRules)

        const blockedUserAgentsParameter = aws_ssm.StringParameter.valueFromLookup(this, props.blockedUserAgentsParameterName, '[]')
        const blockedUserAgentsJson = JSON.parse(blockedUserAgentsParameter)

        const BlockedUserAgentsSchema = z.array(z.string())


        const userAgentList: string[] = []

        const validatedUserAgents = BlockedUserAgentsSchema.parse(blockedUserAgentsJson)
        validatedUserAgents.forEach((useragent, index: number) => {
            userAgentList.push(useragent)
        })

        const userAgentRegexPattern = new aws_wafv2.CfnRegexPatternSet(this, 'userAgentRegexPatternSet', {
            description: 'Blocked user agents',
            name: 'BlockedUserAgents',
            scope: 'REGIONAL',
            regularExpressionList: userAgentList
        })


        const blockedUserAgentRules: any[] = []
        const blockedUserAgentRule: aws_wafv2.CfnWebACL.RuleProperty = {
            name: "blocked-useragents",
            priority: rules.length,
            action: {
                block: {}
            },
            statement: {
                regexPatternSetReferenceStatement: {
                    fieldToMatch: {
                        singleHeader: {
                            Name: "User-Agent"
                        }
                    },
                    arn: userAgentRegexPattern.attrArn,
                    textTransformations: [
                        {
                            type: "NONE",
                            priority: 0
                        }
                    ]
                }
            },
            visibilityConfig: {
                cloudWatchMetricsEnabled: true,
                metricName: "blocked-useragents",
                sampledRequestsEnabled: true
            }
        }

        blockedUserAgentRules.push(blockedUserAgentRule)

        rules = rules.concat(blockedUserAgentRules)



        const cfnWebAcl = new aws_wafv2.CfnWebACL(this, 'WAFWebACL', {
            scope: "REGIONAL",
            defaultAction: {
                allow: {}
            },
            visibilityConfig: {
                cloudWatchMetricsEnabled: true,
                metricName: "SixodpWAF",
                sampledRequestsEnabled: false
            },
            rules: rules
        })

        new aws_wafv2.CfnWebACLAssociation(this, 'WafAssociation', {
            resourceArn: props.loadBalancer.loadBalancerArn,
            webAclArn: cfnWebAcl.attrArn
        })

    }
}