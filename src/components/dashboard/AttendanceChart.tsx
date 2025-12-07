import { PieChart, Pie, Cell, ResponsiveContainer } from "recharts";
import { MoreHorizontal, Users } from "lucide-react";

const data = [
  { name: "Students", value: 84, color: "hsl(var(--primary))" },
  { name: "Teachers", value: 91, color: "hsl(var(--accent))" },
];

export function AttendanceChart() {
  return (
    <div className="chart-card animate-fade-in" style={{ animationDelay: "0.4s" }}>
      <div className="flex items-center justify-between mb-4">
        <h3 className="text-lg font-semibold text-foreground">Attendance</h3>
        <button className="p-1 hover:bg-muted rounded">
          <MoreHorizontal className="w-5 h-5 text-muted-foreground" />
        </button>
      </div>

      <div className="flex items-center justify-center">
        <div className="relative w-40 h-40">
          <ResponsiveContainer width="100%" height="100%">
            <PieChart>
              <Pie
                data={data}
                cx="50%"
                cy="50%"
                innerRadius={45}
                outerRadius={65}
                startAngle={90}
                endAngle={-270}
                paddingAngle={5}
                dataKey="value"
              >
                {data.map((entry, index) => (
                  <Cell key={`cell-${index}`} fill={entry.color} strokeWidth={0} />
                ))}
              </Pie>
            </PieChart>
          </ResponsiveContainer>
          
          {/* Center Icon */}
          <div className="absolute inset-0 flex items-center justify-center">
            <div className="w-14 h-14 rounded-full bg-muted flex items-center justify-center">
              <Users className="w-6 h-6 text-muted-foreground" />
            </div>
          </div>
        </div>
      </div>

      {/* Legend */}
      <div className="flex justify-center gap-8 mt-4">
        <div className="text-center">
          <p className="text-sm text-muted-foreground">Students</p>
          <p className="text-xl font-bold text-primary">84%</p>
        </div>
        <div className="text-center">
          <p className="text-sm text-muted-foreground">Teachers</p>
          <p className="text-xl font-bold text-accent">91%</p>
        </div>
      </div>
    </div>
  );
}
