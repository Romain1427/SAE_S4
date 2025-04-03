package com.example.incidandroid.ui;

import android.content.Intent;
import android.os.Bundle;
import android.widget.Button;

import androidx.appcompat.app.AppCompatActivity;

import com.example.incidandroid.R;
import com.example.incidandroid.ui.connexion.MainActivity;
import com.example.incidandroid.ui.reports.ReportsActivity;
import com.example.incidandroid.ui.reservations.ReservationsActivity;

public class ActivityChooser extends AppCompatActivity {

    private Button toReservationBtn;
    private Button toReportsBtn;
    private String login;

    private Intent intention;
    private Intent parentIntent;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_choice);
        toReservationBtn = findViewById(R.id.to_reservation_btn);
        toReportsBtn = findViewById(R.id.to_reports_btn);

        parentIntent = this.getIntent();
        login = parentIntent.getStringExtra(MainActivity.USERNAME);

        toReportsBtn.setOnClickListener((view) ->  {
            intention = new Intent(ActivityChooser.this, ReportsActivity.class);
            intention.putExtra(MainActivity.CLE_API, parentIntent.getStringExtra(MainActivity.CLE_API));
            startActivity(intention);
        });

        toReservationBtn.setOnClickListener((view) ->  {
            intention = new Intent(ActivityChooser.this, ReservationsActivity.class);

            intention.putExtra(MainActivity.CLE_API,
                    parentIntent.getStringExtra(MainActivity.CLE_API));

            intention.putExtra(MainActivity.USERNAME,
                    parentIntent.getStringExtra(MainActivity.USERNAME));
            startActivity(intention);
        });
    }
}
