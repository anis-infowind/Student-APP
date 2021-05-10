var WORKER_PATH = baseUrl+'/public/js/recorderWorker.js';

var WaveRecorder = function(stream) {
    var context, audioInput, processor, gain, gainFunction, processorFunction;
    var recording = false,
        currCallback;

    context = new AudioContext;
    gainFunction = context.createGain || context.createGainNode;
    gain = gainFunction.call(context);
    audioInput = context.createMediaStreamSource(stream);

    audioInput.connect(gain);

    //WAV VARS
    var leftchannel = [];
    var rightchannel = [];
    var recordingLength = 0;

    sampleRate = context.sampleRate;

    var bufferSize = 2048;

    processorFunction = context.createScriptProcessor || context.createJavaScriptNode;
    processor = processorFunction.call(context, bufferSize, 2, 2);
    /*var worker = new Worker(WORKER_PATH);
    worker.postMessage({
        command: 'init',
        sampleRate: context.sampleRate
    });*/

    processor.onaudioprocess = function(e) {
        if (!recording) return;
        /*worker.postMessage({
            command: 'record',
            buffer: e.inputBuffer.getChannelData(0)
        });*/

        //WAV
        var left = e.inputBuffer.getChannelData(0);
        var right = e.inputBuffer.getChannelData(1);
        // we clone the samples
        leftchannel.push(new Float32Array(left));
        rightchannel.push(new Float32Array(right));
        recordingLength += bufferSize;
        
        /////////////////////////////////////////////////////
    }

    this.record = function() {
        recording = true;
    }
    this.stop = function() {
        recording = false;
    }
    this.exportMP3 = function() {

        /*worker.postMessage({
            command: 'exportMP3'
        });*/
    }

    this.exportWAV = function(){

    	//Colocar div de audio

        var leftBuffer = mergeBuffers ( leftchannel, recordingLength );
        var rightBuffer = mergeBuffers ( rightchannel, recordingLength );

        // we interleave both channels together
        var interleaved = interleave ( leftBuffer, rightBuffer );
        var data = encodeWAV(interleaved);

        // our final binary blob
        var audioBlob = new Blob ( [ data ], { type : 'audio/wav' } );

        var reader = new FileReader();

      	reader.onload = function () {
			audio_recordedb64 = event.target.result;
		};

		reader.readAsDataURL(audioBlob);

		var audioURL = window.URL.createObjectURL(audioBlob);

		micro_audio = audioURL;

        return audioBlob;
    }

    gain.connect(processor);
    processor.connect(context.destination);
};

function mergeBuffers(channelBuffer, recordingLength){
    var result = new Float32Array(recordingLength);
    var offset = 0;
    var lng = channelBuffer.length;
    for (var i = 0; i < lng; i++){
        var buffer = channelBuffer[i];
        result.set(buffer, offset);
        offset += buffer.length;
    }
    return result;
}

function interleave(leftChannel, rightChannel){
    var length = leftChannel.length + rightChannel.length;
    var result = new Float32Array(length);

    var inputIndex = 0;

    for (var index = 0; index < length; ){
        result[index++] = leftChannel[inputIndex];
        result[index++] = rightChannel[inputIndex];
        inputIndex++;
    }
    return result;
}

function encodeWAV(samples){
    var buffer = new ArrayBuffer(44 + samples.length * 2);
    var view = new DataView(buffer);

    /* RIFF identifier */
    writeString(view, 0, 'RIFF');
    /* file length */
    view.setUint32(4, 44 + samples.length * 2, true);
    /* RIFF type */
    writeString(view, 8, 'WAVE');
    /* format chunk identifier */
    writeString(view, 12, 'fmt ');
    /* format chunk length */
    view.setUint32(16, 16, true);
    /* sample format (raw) */
    view.setUint16(20, 1, true);
    /* channel count */
    view.setUint16(22, 2, true); /*STEREO*/
    //view.setUint16(22, 1, true); /*MONO*/
    /* sample rate */
    view.setUint32(24, sampleRate, true);
    /* byte rate (sample rate * block align) */
    view.setUint32(28, sampleRate * 4, true); /*STEREO*/
    //view.setUint32(28, sampleRate * 2, true); /*MONO*/
    /* block align (channel count * bytes per sample) */
    view.setUint16(32, 4, true); /*STEREO*/
    //view.setUint16(32, 2, true); /*MONO*/
    /* bits per sample */
    view.setUint16(34, 16, true);
    /* data chunk identifier */
    writeString(view, 36, 'data');
    /* data chunk length */
    view.setUint32(40, samples.length * 2, true);

    floatTo16BitPCM(view, 44, samples);


    return view;
}

function writeString(view, offset, string){
    for (var i = 0; i < string.length; i++){
        view.setUint8(offset + i, string.charCodeAt(i));
    }
}

function floatTo16BitPCM(output, offset, input){
    for (var i = 0; i < input.length; i++, offset+=2){
        var s = Math.max(-1, Math.min(1, input[i]));
        output.setInt16(offset, s < 0 ? s * 0x8000 : s * 0x7FFF, true);
    }
}
