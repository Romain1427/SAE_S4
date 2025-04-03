package com.example.incidandroid.utils;

import android.content.Context;
import android.content.Intent;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

import com.example.incidandroid.R;
import com.example.incidandroid.model.ReportSeverity;
import com.example.incidandroid.model.ReservationCard;
import com.example.incidandroid.ui.reservations.ReservationsActivity;

import java.util.List;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class CustomListReservationAdapter extends BaseAdapter {

    private List<ReservationCard> listData;
    private LayoutInflater layoutInflater;
    private Context context;
    private String formattedDate;
    private ReservationsActivity reservationsActivity;
    public CustomListReservationAdapter(ReservationsActivity activity, Context aContext, List<ReservationCard> listData) {
        this.context = aContext;
        this.listData = listData;
        layoutInflater = LayoutInflater.from(aContext);
        this.reservationsActivity = activity;
    }

    @Override
    public int getCount() {
        return listData.size();
    }

    @Override
    public Object getItem(int position) {
        return listData.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    public View getView(int position, View convertView, ViewGroup parent) {
        ViewHolder holder;
        if (convertView == null) {
            // Récupération des zones d'affichage pour chaque item
            convertView = layoutInflater.inflate(R.layout.reservation_list_item, null);
            holder = new ViewHolder();
            holder.reservationName = (TextView) convertView.findViewById(R.id.nom_resa);
            holder.reservationRoom = (TextView) convertView.findViewById(R.id.nom_salle_reservee);
            holder.reservationDate = (TextView) convertView.findViewById(R.id.heures_resa);
            holder.reservationStatus = (ImageView) convertView.findViewById(R.id.icon_status);
            holder.btnReport = (Button) convertView.findViewById(R.id.signal_problem_btn);
            convertView.setTag(holder);
        } else {
            holder = (ViewHolder) convertView.getTag();
        }

        // Modification des données récupérées

        ReservationCard resa = this.listData.get(position);

        resa.setBtnReport(holder.btnReport);

        String dateStr = resa.getReservationData().getReservationDate();
        formattedDate = getFrenchDateFormat(dateStr);

        holder.reservationName.setText("Réservation du " + formattedDate);
        holder.reservationRoom.setText(resa.getReservationData().getReservationRoom());
        holder.reservationDate.setText(
                resa.getReservationData().getStartHour()
                + " - "
                + resa.getReservationData().getEndHour());

        Integer maxReportSeverity = resa.getReservationData().getIdIncidentMax();
        if (maxReportSeverity == null) { // L'api renvoi null <=> Donc pas de signalements sur la résa
            maxReportSeverity = 5;
        }
        int imageId = ReportSeverity.getSeverityById(maxReportSeverity).getImgId();

        holder.reservationStatus.setImageResource(imageId);

        return convertView;
    }

    static class ViewHolder {
        TextView reservationName;
        TextView reservationRoom;
        TextView reservationDate;
        ImageView reservationStatus;
        Button btnReport;
    }

    /**
     * Transforme une date du format YYYY-MM-DD au format DD/MM/YYYY
     * @param date une date au format : YYYY-MM-DD
     * @return La date au format : DD/MM/YYYY
     */
    public static String getFrenchDateFormat(String date) {
        String regex = "(\\d{4})-(\\d{2})-(\\d{2})"; // YYYY-MM-DD
        String newDate;

        Pattern pattern = Pattern.compile(regex);
        Matcher matcher = pattern.matcher(date);

        newDate = "";
        if (matcher.matches()) {
            newDate = matcher.group(3) + "/" + matcher.group(2) + "/" + matcher.group(1);
        } else {
            System.out.println("Format de date incorrect !");
        }

        return newDate;
    }

}