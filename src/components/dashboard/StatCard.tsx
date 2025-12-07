import { ReactNode } from "react";
import { cn } from "@/lib/utils";

interface StatCardProps {
  icon: ReactNode;
  label: string;
  value: string | number;
  iconBgColor?: string;
  className?: string;
}

export function StatCard({ icon, label, value, iconBgColor = "bg-blue-100", className }: StatCardProps) {
  return (
    <div className={cn("stat-card flex items-center gap-4 animate-fade-in", className)}>
      <div className={cn("w-12 h-12 rounded-full flex items-center justify-center", iconBgColor)}>
        {icon}
      </div>
      <div>
        <p className="text-sm text-muted-foreground">{label}</p>
        <p className="text-2xl font-bold text-foreground">{value}</p>
      </div>
    </div>
  );
}
