package com.example.incidandroid.ui.reports;

import static android.view.View.GONE;
import static android.view.View.VISIBLE;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import androidx.appcompat.app.AppCompatActivity;

import com.example.incidandroid.R;
import com.example.incidandroid.model.Report;
import com.example.incidandroid.model.ReportCard;
import com.example.incidandroid.ui.connexion.MainActivity;
import com.example.incidandroid.utils.Api;
import com.example.incidandroid.utils.CustomListReportAdapter;
import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;

import java.util.ArrayList;
import java.util.List;

public class ReportsActivity extends AppCompatActivity {

    RelativeLayout spinningLoader;
    RelativeLayout loadingMessageContainer;
    TextView loadingMessage;

    @Override
    protected void onResume() {
        super.onResume();
        loadReports();
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        loadReports();
    }

    public void loadReports() {
        setContentView(R.layout.reports_activity);
        Api api = Api.getInstance();

        spinningLoader = findViewById(R.id.loadingPanel);
        loadingMessageContainer = findViewById(R.id.loadingTextView);
        loadingMessage = findViewById(R.id.loadingText);

        loadingMessageContainer.setVisibility(VISIBLE);
        spinningLoader.setVisibility(VISIBLE);
        loadingMessage.setText(getString(R.string.report_loading_msg));

        Intent receivedIntent = getIntent();
        String API_KEY = receivedIntent.getStringExtra(MainActivity.CLE_API);

        api.signalements(getApplicationContext(), API_KEY, "", jsonArray -> {
            Gson gson = new Gson();
            // result = JSONArray <=> Conversion en donnée exploitable
            JsonArray jsonResult = gson.fromJson(jsonArray.toString(), JsonArray.class);
            List<ReportCard> list = new ArrayList<>();
            for (JsonElement reportData : jsonResult) {
                // Convertit chaque objet JSON en instance de Reservation
                list.add(new ReportCard(gson.fromJson(reportData, Report.class),
                        ReportsActivity.this, API_KEY));
            }
            createReports(list);
        }, error -> Log.e("Incidandroid", "Impossible de récupérer la liste des réservations"));
    }

    public void createReports(List<ReportCard> reportsData) {


        if (!reportsData.isEmpty()) {
            final ListView reportsUI = findViewById(R.id.listView);

            reportsUI.setAdapter(new CustomListReportAdapter(ReportsActivity.this
                    ,this, reportsData));


        } else {
            TextView errorPlaceholder = findViewById(R.id.msg_erreur);
            errorPlaceholder.setText(getString(R.string.no_reports_msg));
        }
        loadingMessageContainer.setVisibility(GONE);
        spinningLoader.setVisibility(GONE);
    }
}
