$('.barcode-list').each(function (index, item) {

    $(item).barcode(
        $(item).attr("custom-id"),
        "code128",
        {
            showHRI: false,
            barWidth: 2,
            barHeight: 30,
        }
    );
})


$("#barCode").barcode(
    $("#barCode").attr("custom-id"),
    "code128",
    {
        showHRI: true,
        barWidth: 2,
    }
);



let qr_code_element = document.querySelector(".qr-code");

function generate() {
    qr_code_element.style = "";
    var qrcode = new QRCode(qr_code_element, {
        text: `${$("#barCode").attr("custom-id")}`,
        width: 128, //128
        height: 128,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
}

generate($($("#qrCode")))


function downloadBadge() {
    const element = document.querySelector(".container");

    html2canvas(element, {
        scale: 1,
        windowWidth: 2000,
        useCORS: true,
        width: 1200,
        height: 1200,
    }).then(canvas => {

        const imgData = canvas.toDataURL("image/png");
        const { jsPDF } = window.jspdf;

        const pdf = new jsPDF({
            orientation: "portrait",
            unit: "mm",
            format: "a4"
        });

        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;
        pdf.addImage(imgData, "PNG", 0, 0, pdfWidth, pdfHeight);
        pdf.save($("#barCode").attr("custom-id") + "-e-Badge.pdf");
    });
}
// window.onload = function () {
//     window.print();
// }