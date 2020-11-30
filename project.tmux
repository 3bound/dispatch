#!/bin/sh
tmux new -s dispatch -d
tmux rename-window -t 0 vim
tmux send-keys -t dispatch:vim "vim" C-m
tmux new-window -t dispatch -n shell -d
tmux split-window -t dispatch:shell -v
tmux select-window -t dispatch:vim
tmux attach -t dispatch
