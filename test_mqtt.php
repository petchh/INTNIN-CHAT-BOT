<script src="lib/mqtt.js"></script>
<script type="text/javascript">
// Create a client instance

client = new Paho.MQTT.Client("iot.eclipse.org", Number(443), "TTDN021");
 
// set callback handlers
client.onConnectionLost = onConnectionLost;
client.onMessageArrived = onMessageArrived;
 
// connect the client
client.connect({    
  onSuccess: onConnect, 
  useSSL: true
});
 
// called when the client connects
function onConnect() {
  // Once a connection has been made, make a subscription and send a message.
  console.log("onConnect");
  client.subscribe("INTNIN_LAB/CHAT_BOT");

}
 
// called when the client loses its connection
function onConnectionLost(responseObject) {
  if (responseObject.errorCode !== 0) {
    console.log("onConnectionLost:"+responseObject.errorMessage);
  }
}
 
// called when a message arrives
function onMessageArrived(message) {
  console.log("onMessageArrived:"+message.payloadString);
}
 
function pub() {
  console.log("<?php echo $content; ?>");
  message = new Paho.MQTT.Message("<?php echo $text; ?>");
  message.destinationName = "INTNIN_LAB/CHAT_BOT";
  client.send(message); 
} 
</script>
