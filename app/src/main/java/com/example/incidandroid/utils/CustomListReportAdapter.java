package com.example.incidandroid.utils;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Button;
import android.widget.TextView;

import com.example.incidandroid.R;
import com.example.incidandroid.model.Report;
import com.example.incidandroid.model.ReportCard;
import com.example.incidandroid.model.ReportSeverity;

import java.util.List;

public class CustomListReportAdapter extends BaseAdapter {

    private List<ReportCard> listData;
    private LayoutInflater layoutInflater;
    private Context context;
    private String formattedDate;
    private Activity activity;

    public CustomListReportAdapter(Activity activity, Context aContext, List<ReportCard> listData) {
        this.context = aContext;
        this.listData = listData;
        layoutInflater = LayoutInflater.from(aContext);
        this.activity = activity;
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
        return position; // Non utilisé
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        CustomListReportAdapter.ViewHolder holder;
        if (convertView == null) {
            // Récupération des données d'affichage dans chaque item
            convertView = layoutInflater.inflate(R.layout.report_list_item, null);
            holder = new CustomListReportAdapter.ViewHolder();
            holder.reportDescription = (TextView) convertView.findViewById(R.id.report_item_description);
            holder.reportSeverityName = (TextView) convertView.findViewById(R.id.report_item_severity_holder);
            holder.reportReservationDesc = (TextView) convertView.findViewById(R.id.report_item_reservation);
            holder.reportSummary = (TextView) convertView.findViewById(R.id.report_item_summary);
            holder.reportSeverityDesc = (TextView) convertView.findViewById(R.id.report_item_severity_desc_holder);
            holder.reportModifyBtn = (Button) convertView.findViewById(R.id.btn_modify_report);
            holder.reportDeleteBtn = (Button) convertView.findViewById(R.id.btn_delete_report);
            convertView.setTag(holder);
        } else {
            holder = (CustomListReportAdapter.ViewHolder) convertView.getTag();
        }

        // Récupération des données de l'item de la liste
        ReportCard report = this.listData.get(position);
        Report reportData = report.getReportData();

        report.setBtnModify(holder.reportModifyBtn);
        report.setBtnDelete(holder.reportDeleteBtn);

        // Texte décrivant la réservation associé au report

        String reservationReportDesc = context.getString(R.string.reservation_name_report,
                CustomListReservationAdapter.getFrenchDateFormat(reportData.getReservationDate()),
                reportData.getStartHour(), reportData.getEndHour(),
                reportData.getRoomName(), reportData.getActivityName());

        ReportSeverity currentSeverity
                = ReportSeverity.getSeverityById(reportData.getIncident());

        holder.reportDescription.setText(reportData.getDescription());
        holder.reportSummary.setText(reportData.getResume());
        holder.reportReservationDesc.setText(reservationReportDesc);
        holder.reportSeverityName.setText(R.string.type_report_severity);
        holder.reportSeverityDesc.setText(currentSeverity.getDesc());

        return convertView;
    }

    /**
     * Permet de stocker les données de la vue pour chaque item
     * de la liste des incidents
     */
    static class ViewHolder {
        TextView reportSummary;
        TextView reportReservationDesc;
        TextView reportDescription;
        TextView reportSeverityName;
        TextView reportSeverityDesc;
        Button reportModifyBtn;
        Button reportDeleteBtn;
    }
}
