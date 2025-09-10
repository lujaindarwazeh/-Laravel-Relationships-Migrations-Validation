#!/bin/bash

# Config: change to your services and commands
MYSQL_SERVICE="mysql"
ARTISAN_PID_FILE="/tmp/artisan_pid.txt"

# Function to check if MySQL is running (Git Bash compatible)
check_mysql() {
    (echo > /dev/tcp/127.0.0.1/3306) >/dev/null 2>&1
    return $?
}

# Function to kill artisan or other commands safely
kill_artisan() {
    if [ -f "$ARTISAN_PID_FILE" ]; then
        PID=$(cat "$ARTISAN_PID_FILE")
        if ps -p $PID > /dev/null; then
            echo "Killing artisan process $PID because MySQL is down..."
            kill -9 $PID
        fi
        rm -f "$ARTISAN_PID_FILE"
    fi
}

# Run artisan (or any command) in background
start_artisan() {
    echo "Starting artisan serve..."
    php artisan serve &
    echo $! > $ARTISAN_PID_FILE
}

# Main loop
while true; do
    if check_mysql; then
        if [ ! -f "$ARTISAN_PID_FILE" ]; then
            start_artisan
        fi
    else
        echo "MySQL is down!"
        kill_artisan
    fi
    sleep 5
done

#!/bin/bash

# # Function to check if MySQL is running
# check_mysql() {
#     (echo > /dev/tcp/127.0.0.1/3306) >/dev/null 2>&1
#     return $?
# }

# # Kill ALL artisan commands (serve, queue, schedule, etc.)
# kill_all_artisan() {
#     echo "Killing all php artisan commands because MySQL is down..."
#     # Find any running artisan processes and kill them
#     ps aux | grep "php artisan" | grep -v grep | awk '{print $2}' | xargs -r kill -9
# }

# # Main loop
# while true; do
#     if check_mysql; then
#         echo "✅ MySQL is UP"
#     else
#         echo "❌ MySQL is DOWN"
#         kill_all_artisan
#     fi
#     sleep 5
# done
