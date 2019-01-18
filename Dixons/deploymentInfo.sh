#!/usr/bin/env bash
#
# autor: Vladislav Urbánek, 2019
# použití: deploumentinfo.sh argument "druhý argument" "třetí argument" argument "pátý argument" ...

defined=(name version date)

message="New deployment:\n"
title="New deployment info"
slackHook="https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX"
channel="CI info"
hostname="CIINFO"

i=0
for arguments in "$@"
do
    [ ${array[key]} ] && message "$message $defined[$i]: $arguments\n"
    i=$((i + 1))
done


read -d '' payLoad << EOF
{
        "channel": "#${channel}",
        "username": "$(hostname)",
        "icon_emoji": ":package:",
        "attachments": [
            {
                "fallback": "${title}",
                "color": "good",
                "title": "${title}",
                "fields": [{
                    "title": "message",
                    "value": "${message}",
                    "short": false
                }]
            }
        ]
    }
EOF


statusCode=$(curl \
        --write-out %{http_code} \
        --silent \
        --output /dev/null \
        -X POST \
        -H 'Content-type: application/json' \
        --data "${payLoad}" ${slackUrl})

echo ${statusCode}
