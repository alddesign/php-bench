$src = 'D:\xampp\htdocs\php-bench\';

$dest = '\\192.168.0.250\html\php-bench\';
Remove-Item -Path $dest -Recurse -Force -ErrorAction SilentlyContinue
Get-ChildItem $src -Recurse -Exclude ".git" | Copy-Item -Destination {Join-Path $dest  $_.FullName.Substring($src.length)}

$dest = '\\192.168.0.3\html\php-bench\';
Remove-Item -Path $dest -Recurse -Force -ErrorAction SilentlyContinue
Get-ChildItem $src -Recurse -Exclude ".git" | Copy-Item -Destination {Join-Path $dest  $_.FullName.Substring($src.length)}