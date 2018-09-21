# Lab B Tank Control

## Dependencies
- pigpio 1.1.2
- node 10.8.x

## Connecting
Hostnames:
- `left-tank` - SD card marked "L"
- `right-tank` - SD card marked "R"
```
ssh pi@left-tank
ssh pi@right-tank
```

## Building
```
nvm install 10.8.0
nvm use 10.8.0
sudo apt-get install pigpio
npm install
```
