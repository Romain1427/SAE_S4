package com.example.incidandroid.ui.reservations;

import static android.view.View.GONE;
import static android.view.View.VISIBLE;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.widget.ListView;
import android.widget.RelativeLayout;
import android.widget.TableLayout;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.example.incidandroid.R;
import com.example.incidandroid.model.Report;
import com.example.incidandroid.model.ReportCard;
import com.example.incidandroid.ui.connexion.MainActivity;
import com.example.incidandroid.ui.reports.ReportsActivity;
import com.example.incidandroid.utils.Api;
import com.example.incidandroid.utils.CustomListReportAdapter;
import com.example.incidandroid.utils.CustomListReservationAdapter;
import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;

import java.util.ArrayList;
import java.util.List;

public class DetailedReservationActivity extends AppCompatActivity {
    private TextView detailReservant;
    private TextView detailDate;
    private TextView detailHours;
    private TextView detailRoom;
    private TextView detailActivity;
    TableLayout spinningLoader;
    TextView loadingMessage;
    Intent parentIntent;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.reservation_details);

        detailActivity = findViewById(R.id.detail_activity);
        detailDate = findViewById(R.id.detail_date);
        detailReservant = findViewById(R.id.detail_reservant);
        detailRoom = findViewById(R.id.detail_room);
        detailHours = findViewById(R.id.detail_time);

        parentIntent = getIntent();

        detailReservant.setText(parentIntent.getStringExtra(ReservationsActivity.DETAILS_KEY[0]));
        detailDate.setText(CustomListReservationAdapter.getFrenchDateFormat( // On veut la date au bon format
                parentIntent.getStringExtra(ReservationsActivity.DETAILS_KEY[1])));

        detailHours.setText(parentIntent.getStringExtra(ReservationsActivity.DETAILS_KEY[2]));
        detailRoom.setText(parentIntent.getStringExtra(ReservationsActivity.DETAILS_KEY[3]));
        detailActivity.setText(parentIntent.getStringExtra(ReservationsActivity.DETAILS_KEY[4]));

        loadReports();
    }

    @Override
    protected void onResume() {
        super.onResume();
        loadReports();

    }

    private void loadReports() {
        spinningLoader = findViewById(R.id.loadingPanelDetails);
        loadingMessage = findViewById(R.id.loadingText);

        spinningLoader.setVisibility(VISIBLE);
        loadingMessage.setText("Chargement des incidents ...");

        String API_KEY = parentIntent.getStringExtra(MainActivity.CLE_API);

        Api.getInstance().signalements(getApplicationContext(), API_KEY,
                "/"+parentIntent.getStringExtra(ReservationsActivity.DETAILS_KEY[5]),
                jsonArray -> {
                    Gson gson = new Gson();
                    // result = JSONArray <=> Conversion en donnée exploitable
                    JsonArray jsonResult = gson.fromJson(jsonArray.toString(), JsonArray.class);
                    List<ReportCard> reportsData = new ArrayList<>();
                    for (JsonElement reportData : jsonResult) {
                        // Convertit chaque objet JSON en instance de Reservation
                        reportsData.add(new ReportCard(gson.fromJson(reportData, Report.class),
                                DetailedReservationActivity.this, API_KEY));
                    }
                    TextView errorPlaceholder = findViewById(R.id.msg_erreur);
                    spinningLoader.setVisibility(GONE);
                    if (reportsData.size() > 0) {
                        final ListView reportsUI = (ListView) findViewById(R.id.reservation_reports);

                        reportsUI.setAdapter(new CustomListReportAdapter(
                                DetailedReservationActivity.this
                                ,this, reportsData));

                        errorPlaceholder.setVisibility(GONE);
                    } else {
                        errorPlaceholder.setVisibility(VISIBLE);
                        errorPlaceholder.setText(getString(R.string.no_reports_on_reservation));
                    }

                }, error -> Log.e("Incidandroid", "Impossible de récupérer la liste des réservations"));
    }
}