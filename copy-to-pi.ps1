Remove-Item -Path \\192.168.0.3\html\php-bench\ -Recurse -Force -ErrorAction SilentlyContinue
Remove-Item -Path \\192.168.0.250\html\php-bench\ -Recurse -Force -ErrorAction SilentlyContinue

Copy-Item -Path D:\xampp\htdocs\php-bench\* -Destination \\192.168.0.3\html\php-bench\ -Exclude ".git" -Recurse -Force
Copy-Item -Path D:\xampp\htdocs\php-bench\* -Destination \\192.168.0.250\html\php-bench\ -Exclude ".git" -Recurse -Force
