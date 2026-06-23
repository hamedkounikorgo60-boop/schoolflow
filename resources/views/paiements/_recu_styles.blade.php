<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 13px;
        color: #1e293b;
        background: #fff;
    }
    .recu-wrapper {
        max-width: 680px;
        margin: 0 auto;
        padding: 24px 32px 32px;
    }
    .recu-header { text-align: center; margin-bottom: 12px; }
    .ecole-nom {
        font-size: 17px;
        font-weight: bold;
        color: #1e3a5f;
        margin-bottom: 6px;
        line-height: 1.3;
    }
    .ecole-ligne {
        font-size: 11px;
        color: #475569;
        line-height: 1.5;
    }
    .recu-sep {
        border: none;
        border-top: 1px solid #cbd5e1;
        margin: 14px 0 18px;
    }
    .recu-titre {
        background: #1e40af;
        color: #fff;
        text-align: center;
        font-size: 15px;
        font-weight: bold;
        letter-spacing: 0.05em;
        padding: 10px 16px;
        margin-bottom: 22px;
    }
    .recu-meta { width: 100%; margin-bottom: 24px; }
    .recu-meta td {
        padding: 3px 0;
        vertical-align: top;
        font-size: 13px;
    }
    .meta-label {
        width: 110px;
        font-weight: bold;
        color: #334155;
    }
    .meta-value { color: #0f172a; }
    .recu-montant-box {
        border: 2px solid #1e40af;
        text-align: center;
        padding: 22px 16px;
        margin: 0 auto 20px;
        max-width: 420px;
    }
    .montant-label {
        font-size: 11px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        margin-bottom: 8px;
    }
    .montant-value {
        font-size: 32px;
        font-weight: bold;
        color: #1e40af;
        line-height: 1.1;
    }
    .recu-summary {
        width: 100%;
        background: #fff7ed;
        border: 1px solid #fed7aa;
        margin-bottom: 10px;
    }
    .recu-alert-surplus {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        font-size: 12px;
        padding: 10px 14px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    .recu-summary td {
        padding: 12px 10px;
        text-align: center;
        font-size: 12px;
        vertical-align: middle;
    }
    .summary-item { width: 33%; }
    .summary-label {
        display: block;
        color: #64748b;
        font-size: 11px;
        margin-bottom: 2px;
    }
    .summary-sep {
        width: 1%;
        color: #cbd5e1;
        font-weight: normal;
        padding: 0 4px !important;
    }
    .reste-zero { color: #16a34a; }
    .reste-du { color: #dc2626; }
    .recu-footer-table { width: 100%; margin-top: 8px; }
    .recu-observation {
        font-size: 12px;
        color: #334155;
        padding-top: 4px;
        width: 60%;
    }
    .recu-fait-le {
        font-size: 11px;
        color: #64748b;
        white-space: nowrap;
    }
    @media print {
        body { background: #fff; }
        .no-print { display: none !important; }
        .recu-wrapper { padding: 0; max-width: 100%; }
    }
</style>
