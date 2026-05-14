<?php
session_start();
include "../standard_constants.php";
?>
<!DOCTYPE html>
<html>

<body style="margin-top:87px">

    <h2 style="text-align: center;">Scratch</h2>

    <iframe id="scratchFrame" src="<?= SCRATCHWITHMAKEYMAKEYBUILDPATH ?>" width="100%" height="800"
        style="border:none;"></iframe>


</body>

</html>
<script>
    function getEditorCode(callback) {

        if (typeof callback !== "function") {
            callback = function () { };
        }

        const iframe = document.getElementById("scratchFrame");

        if (!iframe) {
            console.log("iframe not found");
            callback({ code: null });
            return;
        }

        // function handleMessage(event) {

        //     // 🔥 IMPORTANT: origin check
        //     if (event.origin !== "<?= SCRATCHWITHMAKEYMAKEYBUILDPATH ?>") return;

        //     if (event.data && event.data.type === "SCRATCH_CODE") {

        //         console.log("SCRATCH DATA:", event.data);

        //         callback({
        //             code: event.data.code
        //         });

        //         window.removeEventListener("message", handleMessage);
        //     }
        // }

        const allowedOrigin = new URL("<?= SCRATCHWITHMAKEYMAKEYBUILDPATH ?>").origin;

        function handleMessage(event) {

            if (event.origin !== allowedOrigin) {
                return;
            }

            if (event.data && event.data.type === "SCRATCH_CODE") {

                console.log("✅ SCRATCH DATA RECEIVED");

                callback({
                    code: event.data.code
                });

                window.removeEventListener("message", handleMessage);
            }
        }

        window.addEventListener("message", handleMessage);

        // 🔥 send request
        iframe.contentWindow.postMessage(
            { type: "GET_SCRATCH_CODE" },
            "<?= SCRATCHWITHMAKEYMAKEYBUILDPATH ?>"
        );
    }
    
    
    // function getEditorCode(callback) {

    //     if (typeof callback !== "function") {
    //         callback = function(){};
    //     }

    //     try {

    //         const scratchFrame = document.getElementById("scratchFrame");

    //         if (!scratchFrame) {
    //             console.log("scratchFrame not found");
    //             callback({code:null});
    //             return;
    //         }

    //         const vm = scratchFrame.contentWindow.vm;

    //         if (!vm) {
    //             console.log("Scratch VM not ready");
    //             callback({code:null});
    //             return;
    //         }

    //         vm.saveProjectSb3().then(function(blob){

    //             const reader = new FileReader();

    //             reader.onload = function(){
    //                 callback({
    //                     code: reader.result
    //                 });
    //             };

    //             reader.readAsDataURL(blob);

    //         });

    //     } catch(e){
    //         console.error(e);
    //         callback({code:null});
    //     }
    // }

</script>
<!-- <script>
function setEditorCode(data){

    if(!data || !data.code){
        console.log("No scratch file found");
        return;
    }

    let filePath = data.code;
console.log("Loading project scratch:", filePath);
   

    fetch(filePath)
    .then(r => r.arrayBuffer())
    .then(buffer => {

        const blob = new Blob([buffer], { type: "application/octet-stream" });

        const wait = setInterval(()=>{

            if (
                window.vm &&
                typeof window.vm.loadProject === "function"
            ) {

                window.vm.loadProject(blob);
                clearInterval(wait);

                console.log("✅ Scratch project loaded");

            }

        },200);

    })
    .catch(err => {
        console.error("File load error:", err);
    });
}
</script> -->