#!/bin/bash

nbrVisits=0
nbrActions=0

while IFS= read -r line; do
    # ignore bot and security check requests
    bot="[bB]ot|OpenVAS-VT";
    if [[ "$line" =~ $bot ]]; then
        continue;
    fi
    
    browser="Chrome|Edg|Safari|OPR|Firefox";
    if [[ "$line" =~ $browser ]]; then
    # filter http code (2xx) by using regex of ' <http code> XY ' 
      code="[[:space:]]2[[:digit:]]{2}[[:space:]][[:digit:]]+[[:space:]]"
      if [[ "$line" =~ "GET" && $line =~ $code ]]; then
        ((nbrVisits++))
      requests="PUT|POST|DELETE"
      elif [[ "$line" =~ $requests ]]; then
        ((nbrActions++))
      fi
    fi
done < $1

res="{\"visits\": $nbrVisits, \"actions\": $nbrActions}"

curl -X POST -H "Authorization: Bearer tOcUstqY7iJxBkN-LSd2PGH/!wtkvXSXpj5m6394rAED?Dd5oG/ysnA69UK!L7oW" -F "value=$res" https://biigle.de/api/v1/kpis -v
