import { useState } from "react";
import {
  BarChart,
  Bar,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  Legend,
} from "recharts";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";

interface EarningsChartProps {
  data?: Array<{
    month: string;
    month_name: string;
    earnings: number;
    expenses: number;
  }>;
}

export function EarningsChart({ data: propData }: EarningsChartProps) {
  const [year, setYear] = useState(new Date().getFullYear().toString());

  // Transform API data to chart format
  const data = propData && propData.length > 0
    ? propData.map((item) => ({
        month: item.month_name || item.month,
        earnings: parseFloat(item.earnings) || 0,
        expense: parseFloat(item.expenses) || 0,
      }))
    : [
        { month: "Jan", earnings: 0, expense: 0 },
        { month: "Feb", earnings: 0, expense: 0 },
        { month: "Mar", earnings: 0, expense: 0 },
        { month: "Apr", earnings: 0, expense: 0 },
        { month: "May", earnings: 0, expense: 0 },
        { month: "Jun", earnings: 0, expense: 0 },
        { month: "Jul", earnings: 0, expense: 0 },
        { month: "Aug", earnings: 0, expense: 0 },
        { month: "Sep", earnings: 0, expense: 0 },
        { month: "Oct", earnings: 0, expense: 0 },
        { month: "Nov", earnings: 0, expense: 0 },
        { month: "Dec", earnings: 0, expense: 0 },
      ];

  return (
    <div className="chart-card animate-fade-in" style={{ animationDelay: "0.1s" }}>
      <div className="flex items-center justify-between mb-6">
        <div>
          <h3 className="text-lg font-semibold text-foreground">Total Earnings</h3>
          <div className="flex items-center gap-4 mt-2">
            <div className="flex items-center gap-2">
              <div className="w-3 h-3 rounded-full bg-primary" />
              <span className="text-sm text-muted-foreground">Earnings</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="w-3 h-3 rounded-full bg-accent" />
              <span className="text-sm text-muted-foreground">Expense</span>
            </div>
          </div>
        </div>
        <Select value={year} onValueChange={setYear}>
          <SelectTrigger className="w-24">
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="2022">2022</SelectItem>
            <SelectItem value="2023">2023</SelectItem>
            <SelectItem value="2024">2024</SelectItem>
            <SelectItem value="2025">2025</SelectItem>
          </SelectContent>
        </Select>
      </div>

      <ResponsiveContainer width="100%" height={280}>
        <BarChart data={data} barGap={4}>
          <CartesianGrid strokeDasharray="3 3" stroke="hsl(var(--border))" vertical={false} />
          <XAxis 
            dataKey="month" 
            axisLine={false} 
            tickLine={false}
            tick={{ fill: "hsl(var(--muted-foreground))", fontSize: 12 }}
          />
          <YAxis 
            axisLine={false} 
            tickLine={false}
            tick={{ fill: "hsl(var(--muted-foreground))", fontSize: 12 }}
            tickFormatter={(value) => `${value / 1000}K`}
          />
          <Tooltip
            contentStyle={{
              backgroundColor: "hsl(var(--card))",
              border: "1px solid hsl(var(--border))",
              borderRadius: "8px",
            }}
            formatter={(value: number) => [`$${value.toLocaleString()}`, ""]}
          />
          <Bar 
            dataKey="earnings" 
            fill="hsl(var(--primary))" 
            radius={[4, 4, 0, 0]}
            maxBarSize={20}
          />
          <Bar 
            dataKey="expense" 
            fill="hsl(var(--accent))" 
            radius={[4, 4, 0, 0]}
            maxBarSize={20}
          />
        </BarChart>
      </ResponsiveContainer>
    </div>
  );
}
