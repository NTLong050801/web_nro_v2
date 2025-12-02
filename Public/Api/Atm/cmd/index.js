const nodeCmd = require('node-cmd');
const axios = require('axios');

var Pusher = require('pusher-client');

function delay(time) {
  return new Promise(resolve => setTimeout(resolve, time));
} 


async function main(challenge){
  console.log('challenge: ' + challenge);
	nodeCmd.run('adb shell am force-stop com.VCB');
	await delay(100)

	nodeCmd.run('adb shell am start com.VCB/.activities.home.SplashActivity');
	await delay(1500)
	nodeCmd.run('adb shell input tap 746 2087');// toa do nut smart otp ( x , y)
	await delay(1000)
	nodeCmd.run('adb shell input text 271219');// ma pin
	await delay(500)
	nodeCmd.run('adb shell input tap 738 1369'); // nut tiep tuc
	await delay(500)
	nodeCmd.run('adb shell input tap 760 901'); // toa do cho nhap ma gd
	await delay(500)
	nodeCmd.run('adb shell input text ' + challenge); 
	await delay(300)
	nodeCmd.run('adb shell input tap 767 1140'); // nut tiep tuc
	await delay(500)
	nodeCmd.run('adb shell input tap 1060 1190');  // nut sao chep

	await delay(200)
	nodeCmd.run('adb shell am start ca.zgrs.clipper/.Main');

	await delay(200)
	nodeCmd.run('adb shell am broadcast -a clipper.get', (err, data, stderr) => {
		const pattern = /data="(\d+)"/;
		const match = data.match(pattern);

		if (match) {
		  const extractedData = match[1];
		  console.log('Smart OTP: ' + extractedData);
		  axios('http://localhost/api-vietcombank-chuyentien/saveotp.php?otp=' + extractedData)
		}
	});
}


// async function main(challenge){

// 	nodeCmd.run('adb shell am force-stop com.VCB');
// 	await delay(100)
// 	nodeCmd.run('adb shell am start com.VCB/.activities.home.SplashActivity');
// 	await delay(1500)
// 	nodeCmd.run('adb shell input tap 746 2087');
// 	await delay(1000)
// 	nodeCmd.run('adb shell input text 271219');
// 	await delay(1000)
// 	nodeCmd.run('adb shell input tap 738 1369');
// 	await delay(500)
// 	nodeCmd.run('adb shell input tap 760 901');
// 	await delay(500)
// 	nodeCmd.run('adb shell input text ' + challenge);
// 	await delay(500)
// 	nodeCmd.run('adb shell input tap 767 1140');
// 	await delay(500)
// 	nodeCmd.run('adb shell input tap 1060 1190');

// 	await delay(100)
// 	nodeCmd.run('adb shell am start ca.zgrs.clipper/.Main');

// 	await delay(500)
// 	nodeCmd.run('adb shell am broadcast -a clipper.get', (err, data, stderr) => {
// 		const pattern = /data="(\d+)"/;
// 		const match = data.match(pattern);

// 		if (match) {
// 		  const extractedData = match[1];
// 		  console.log(extractedData);
// 		  axios('http://localhost/api-vietcombank-chuyentien/saveotp.php?otp=' + extractedData)
// 		}
// 	});
// }




var pusher = new Pusher('f934b82d669a20642e50', {
      cluster: 'ap1'
});

var channel = pusher.subscribe('my-channel');
channel.bind('my-event', function(data) {
	main(data.challenge);
	console.log();
});

// nodeCmd.run('dir', (err, data, stderr) => console.log(data));

// main('10000000');