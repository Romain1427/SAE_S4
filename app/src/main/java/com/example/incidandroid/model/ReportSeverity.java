package com.example.incidandroid.model;


import com.example.incidandroid.R;

public enum ReportSeverity {
    NEUTRAL(5, "OK", "Pas d'incidents", R.drawable.accept),
    LOW(4, "leger", "incident sans aucun impact sur l'usage de la salle", R.drawable.alert),
    MEDIUM(3, "moyen", "incident peu gênant", R.drawable.attention),
    STRONG(2, "fort", "Incident très gênant", R.drawable.exclamation),
    EXTREME(1, "extreme", "Salle inutilisable", R.drawable.forbidden);

    private int severityIndex;
    private String name;
    private String desc;
    private int imgId;
    private ReportSeverity(int severityIndex, String name, String desc,
                           int imgId) {
        this.severityIndex = severityIndex;
        this.name = name;
        this.desc = desc;
        this.imgId = imgId;
    }

    public int getSeverityIndex() {
        return severityIndex;
    }

    public String getName() {
        return name;
    }

    public String getDesc() {
        return desc;
    }

    public static ReportSeverity getSeverityById(int severityId) {
        ReportSeverity result;

        result = null; // Cas limite
        for (ReportSeverity reportSeverity:
                ReportSeverity.values()) {
            if (reportSeverity.severityIndex == severityId) {
                result = reportSeverity;
            }
        }

        return result;
    }

    public int getImgId() {
        return imgId;
    }
}
