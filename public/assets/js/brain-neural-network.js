if(root.LoggedInUser.userIsAdmin){
var net = new brain.NeuralNetwork({ activation: 'leaky-relu' });
var BrainData = [];
$.get("/controller/api.php?a=jsonNeuralNetwork", function() {
    //loading
  })
  .done(function(data) {
    for (let i = 0; i < data.NeuralNetwork.length; i++) {
      BrainData.push({ input: data.NeuralNetwork[i], output: { allow: parseFloat(data.NeuralNetwork[i].output) } })
      delete BrainData[i].input.output;
      for (var key in BrainData[i].input) {
        BrainData[i].input[key] = parseFloat(BrainData[i].input[key]);
      }
    }
    net.train(BrainData);
    //BrainData[0].input = BrainDataTemp;
  })
  .fail(function() {
    alert("error");
  })


var output;

function simAI(array) {
  output = net.run(array); // [0.987]
  console.log(output[0]);
}
}