import React from "react";
import ExcelJS from "exceljs";

const ExcelReportButton = ({ data }) => {
    const generateReport = () => {
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet("Contestable Funds Report");

        // Agrega encabezados
        worksheet.addRow([
            "ID",
            "Nombre",
            "Institución",
            "Región",
            "País",
            "Fecha de Inicio",
            "Fecha de Fin",
            "Estado",
            "Resumen",
            "Presupuesto",
            "Link",
            "OCDE",
            "ODS",
            "CRL",
            "TRL",

        ]);

        // Agrega datos
        data.forEach((fund) => {
            const ocdeNames = fund.ocde.map(
                (ocde) => `${ocde.code} - ${ocde.name}`
            );
            const odsNames = fund.ods.map(
                (ods) => `${ods.name} - ${ods.description}`
            );

            worksheet.addRow([
                fund.id,
                fund.name,
                fund.institution,
                fund.region,
                fund.country_id,
                fund.start_date,
                fund.end_date,
                fund.status,
                fund.summary,
                fund.budget,
                fund.link,
                ocdeNames.join(", "), 
                odsNames.join(", "),
                `${item.crl.name}-${item.crl.description}`,
                `${item.trl.name}-${item.trl.description}`,
            ]);
        });

        // Genera un blob del archivo Excel
        workbook.xlsx.writeBuffer().then((buffer) => {
            const blob = new Blob([buffer], {
                type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            });

            // Crea un objeto de URL y descarga el archivo
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.href = url;
            a.download = "ReporteFondosConcursables.xlsx";
            a.click();
        });
    };

    return (
        <button
            onClick={generateReport}
            className="rounded-md bg-green-500 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-500 mr-3"
        >
            Reporte
        </button>
    );
};

export default ExcelReportButton;
