package com.example.incidandroid.model;

import android.content.Intent;
import android.util.Log;
import android.widget.Button;

import com.example.incidandroid.ui.connexion.MainActivity;
import com.example.incidandroid.ui.reports.ReportingActivity;
import com.example.incidandroid.ui.reservations.ReservationsActivity;
import com.example.incidandroid.utils.Api;
import com.google.gson.Gson;
import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;

import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

/**
 *     {
 *             - idReservation   : R000001
 *             - reservationRoom : Salle picasso
 *             - reservationActivity : Réunion
 *             - reservationDate : 2025-12-31
 *             - startHour      : 12:00:00
 *             - endHour        : 17:00:00
 *     }
 */
public class ReservationCard {
    private final static String[] RESERVATION_KEYS
            = {"id", "date"};
    private Reservation reservationData;
    private ReservationsActivity activity;
    private Button btnReport;
    private String apiKey;
    public ReservationCard(Reservation reservationData, ReservationsActivity activity, String apiKey) {
        this.reservationData = reservationData;
        this.activity = activity;
        this.apiKey = apiKey;
    }

    public Reservation getReservationData() {
        return reservationData;
    }

    public void setBtnReport(Button btnReport) {
        this.btnReport = btnReport;
        btnReport.setOnClickListener((view) -> {
            Intent intention = new Intent(activity, ReportingActivity.class);
            intention.putExtra(RESERVATION_KEYS[0], reservationData.getIdReservation());
            intention.putExtra(RESERVATION_KEYS[1], reservationData.getReservationDate());
            intention.putExtra(MainActivity.CLE_API, apiKey);
            activity.startActivity(intention);
        });
    }


    public Button getBtnReport() {
        return btnReport;
    }
}
