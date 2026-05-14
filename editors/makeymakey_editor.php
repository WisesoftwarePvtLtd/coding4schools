<?php
session_start();
include "../standard_constants.php";
?>
<!DOCTYPE html>
<html lang="en">



<body style="margin-top: 85px;">
    <h2 style="text-align: center;">Makey Makey</h2>

    <iframe id="makeyFrame" src="<?= SCRATCHWITHMAKEYMAKEYBUILDPATH ?>" width="100%" height="800" style="border:none;"></iframe>

</body>

</html>
<script>
     function getEditorCode(callback) {

        if (typeof callback !== "function") {
            callback = function () { };
        }

        const iframe = document.getElementById("makeyFrame");

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
    
/* Parent page (add_exercise.php) ye function call karega */
//     function getEditorCode(callback) {

//     if (typeof callback !== "function") {
//         callback = function(){};
//     }

//     try {

//         const frame = document.getElementById("makeyFrame");

//         if (!frame) {
//             console.log("makeyFrame not found");
//             callback({code:null});
//             return;
//         }

//         const vm = frame.contentWindow.vm;

//         if (!vm) {
//             console.log("Makey VM not ready");
//             callback({code:null});
//             return;
//         }

//         vm.saveProjectSb3().then(function(blob){

//             const reader = new FileReader();

//             reader.onload = function(){
//                 callback({
//                     code: reader.result   // base64 data
//                 });
//             };

//             reader.readAsDataURL(blob);

//         });

//     } catch(e){
//         console.error(e);
//         callback({code:null});
//     }
// }

    /* Agar parent page kabhi code set kare */
    function setEditorCode(data) {
        console.log("Scratch editor received data:", data);
    }
</script>