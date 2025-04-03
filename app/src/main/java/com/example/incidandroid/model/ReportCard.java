package com.example.incidandroid.model;

import android.app.Activity;
import android.content.Intent;
import android.util.Log;
import android.widget.Button;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;

import com.example.incidandroid.R;
import com.example.incidandroid.ui.connexion.MainActivity;
import com.example.incidandroid.ui.reports.ReportingActivity;
import com.example.incidandroid.utils.Api;

public class ReportCard {

    public final static String[] REPORT_KEYS
            = {"id", "summary", "description", "reportSeverityId",
                "isItContacted", "reservationDate","is_modified"};

    private Report reportData;
    private Activity activity;
    private Button btnModify;
    private Button btnDelete;
    private String apiKey;
    Intent intention;

    public ReportCard(Report reportData, Activity activity, String apiKey) {
        this.reportData = reportData;
        this.activity = activity;
        this.apiKey = apiKey;
        intention = new Intent(activity, ReportingActivity.class);
    }

    public void setBtnModify(Button btnModify) {
        this.btnModify = btnModify;
        btnModify.setOnClickListener((view) -> {

            Log.i("Statut contact", (reportData.getContact() == 1)+"");
            intention.putExtra(MainActivity.CLE_API, apiKey);
            intention.putExtra(REPORT_KEYS[0], reportData.getId());
            Log.i("Testons - d", getReportData().getId()+"");
            intention.putExtra(REPORT_KEYS[1], reportData.getResume());
            intention.putExtra(REPORT_KEYS[2], reportData.getDescription());
            intention.putExtra(REPORT_KEYS[3], reportData.getIncident());
            intention.putExtra(REPORT_KEYS[4], reportData.getContact() == 1);
            intention.putExtra(REPORT_KEYS[5], reportData.getReservationDate());
            intention.putExtra(REPORT_KEYS[6], true);
            activity.startActivity(intention);
        });
    }

    public void setBtnDelete(Button btnDelete) {
        this.btnDelete = btnDelete;
        btnDelete.setOnClickListener((view) -> {
            new AlertDialog.Builder(activity)
                    .setTitle(activity.getString(R.string.confirm_report_deletion_title))
                    .setMessage(activity.getString(R.string.confirm_report_deletion_desc))
                    .setPositiveButton("Oui", (dialog, which) -> {
                        deleteReport();
                        Toast.makeText(activity,
                                       activity.getString(R.string.report_deletion_succes_msg),
                                       Toast.LENGTH_SHORT).show();
                    })
                    .setNegativeButton("Non", (dialog, which) -> {
                        dialog.dismiss();
                    })
                    .show();
        });
    }

    private void deleteReport() {
        Api.getInstance().deleteReport(reportData.getId(), activity, apiKey,
                (result) -> {
                    Log.i("Volley DELETE success", result.toString());
                    Toast.makeText(activity, result.toString(), Toast.LENGTH_LONG).show();
                    activity.finish();
                }, error -> Log.i("Volley DELETE error : ",
                        error.getMessage().toString()));

        activity.recreate(); // On recharge l'activité après suppression
    }

    public Report getReportData() {
        return reportData;
    }

    public Activity getActivity() {
        return activity;
    }

    public Button getBtnModify() {
        return btnModify;
    }
}
