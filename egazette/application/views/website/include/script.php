<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script src="<?php echo base_url(); ?>assets/frontend/js/jquery.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/frontend/js/bootstrap.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/frontend/js/wow.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/frontend/js/jquery.mCustomScrollbar.concat.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/frontend/js/pagenav.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url(); ?>assets/frontend/js/owl.carousel.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!--<script src="<?php echo base_url(); ?>assets/frontend/js/table.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>-->
<script src="<?php echo base_url(); ?>assets/frontend/js/custom-script-2.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!--<script src="<?php echo base_url(); ?>assets/frontend/js/scrollreveal.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>-->
<script src="<?php echo base_url();?>assets/js/vendor/jquery-ui/jquery-ui.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url();?>assets/frontend/js/moment.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url();?>assets/frontend/js/bootstrap-datetimepicker.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<script src="<?php echo base_url();?>assets/frontend/js/pdf.min.js" type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.7.570/pdf.min.js"></script> -->

<script type="text/javascript" nonce="a6b9a780936c8e980939086f618dded2">
    $('.datepicker').datepicker({
        dateFormat:'yy-mm-dd'
    });
    
    $(document).ready(function(){
        $('#c-active').click(function(){
            $('body').removeClass('contrast-active');
        });

        $('#c-inactive').click(function(){
            $('body').addClass('contrast-active');
        });
    });


    function addHoverSpeechSynthesis(selector, lang) {
        const elements = document.querySelectorAll(selector);

        elements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                const text = element.innerText.trim(); // Get the text content of the element
                
                // Call function to speak the text
                speakText(text, lang); // Pass the language code for speech synthesis
            });

            element.addEventListener('mouseleave', () => {
                window.speechSynthesis.cancel(); // Cancel any ongoing speech synthesis
            });
        });

        function speakText(text, lang) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = lang;
            window.speechSynthesis.speak(utterance);
        }
    }

    // Call the function to make the specific <p> element hoverable for speech synthesis
    addHoverSpeechSynthesis('#hoverable_text', 'en-IN'); // change to en-US if not working..
    addHoverSpeechSynthesis('#cm_message', 'en-US');
</script>

<script>
    //  // PDF.js script to extract text from PDF
    //  async function extractTextFromPDF(pdfUrl) {
    //     const pdf = await pdfjsLib.getDocument(pdfUrl).promise;
    //     let textContent = '';

    //     for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
    //         const page = await pdf.getPage(pageNum);
    //         const text = await page.getTextContent();

    //         text.items.forEach(item => {
    //             textContent += item.str + ' ';
    //         });
    //     }

    //     return textContent;
    // }

    // // Function to handle PDF text extraction and speech synthesis
    // async function handlePDFHover(pdfUrl, lang) {
    //     const pdfText = await extractTextFromPDF(pdfUrl);
    //     speakText(pdfText, lang);
    // }

    // function speakText(text, lang) {
    //     const utterance = new SpeechSynthesisUtterance(text);
    //     utterance.lang = lang;
    //     window.speechSynthesis.speak(utterance);
    // }

    // // Add event listener to all PDF links
    // document.querySelectorAll('.pdf-link').forEach(link => {
    //     link.addEventListener('mouseenter', async (event) => {
    //         const pdfUrl = link.href;
    //         await handlePDFHover(pdfUrl, 'en-IN');
    //     });

    //     link.addEventListener('mouseleave', () => {
    //         window.speechSynthesis.cancel();
    //     });
    // });
</script>


<script>
    // const toggleButton = document.getElementById('lang_chng_btn');
    // const elementsToTranslate = document.querySelectorAll('[data-en][data-or]');

    // toggleButton.addEventListener('click', () => {
    //     const isEnglish = toggleButton.textContent.includes('Odia');
        
    //     elementsToTranslate.forEach(element => {
    //         element.innerHTML = isEnglish ? element.getAttribute('data-or') : element.getAttribute('data-en');
    //     });

    //     toggleButton.textContent = isEnglish ? 'Switch to English' : 'Switch to Odia';
    // });

    const toggleButton = document.getElementById('lang_chng_btn');
    const elementsToTranslate = document.querySelectorAll('[data-en][data-or]');

    toggleButton.addEventListener('click', () => {
       
        const isEnglish = toggleButton.textContent.includes('Odia');
        // console.log(isEnglish)
        elementsToTranslate.forEach(element => {
            // Function to recursively update text content while keeping the structure
            const updateTextNodes = (node, isEnglish) => {
                node.childNodes.forEach(child => {
                    // Check if it's a text node and not an element node
                    if (child.nodeType === Node.TEXT_NODE && child.textContent.trim() !== '') {
                        child.textContent = isEnglish ? node.getAttribute('data-or') : node.getAttribute('data-en');
                    } else if (child.nodeType === Node.ELEMENT_NODE) {
                        // Recursively update child elements
                        updateTextNodes(child, isEnglish);
                    }
                });
            };

            // Apply the update function to each element
            updateTextNodes(element, isEnglish);
        });

        toggleButton.textContent = isEnglish ? 'Switch to English' : 'Switch to Odia';
    });

</script>