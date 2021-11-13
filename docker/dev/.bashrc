#!/usr/bin/env bash

YELLOW="\[\033[0;33m\]"
BLUE="\[\033[34m\]"
GREEN="\[\033[0;32m\]"
CYAN="\[\033[0;36m\]"

_LIGHT_GRAY="\[\033[0;37m\]"

export PS1=$BLUE"\u"$YELLOW"@"$BLUE"\h"$CYAN'[docker]'$YELLOW"["$GREEN"\w"$YELLOW"]"$GREEN": "$_LIGHT_GRAY
