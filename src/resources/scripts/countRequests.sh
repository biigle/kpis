#!/bin/bash
#
# Usage: ./countRequests.sh logfile.log.gz

TOKEN=""
BASE_URL="https://biigle.de"

compute_visits() {
    zgrep -F -e Chrome -e Firefox -e Safari -e OPR -e Edg $1 \
        | cut -d ' ' -f 6,7,9 \
        | cut -c 2-8 \
        | grep -F "GET / 2" \
        | wc -l
}


compute_actions() {
    # The "@" will appear in API requests with token authentication.
    zgrep -F -e PUT -e POST -e DELETE $1 \
        | grep -F -e Chrome -e Firefox -e Safari -e OPR -e Edg -e "@" \
        | grep -F -v heartbeat \
        | wc -l
}

nbrVisits=$(compute_visits $1)
nbrActions=$(compute_actions $1)

curl \
    -X POST \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d "{\"visits\": $nbrVisits, \"actions\": $nbrActions}" \
    ${BASE_URL}/api/v1/kpis