import { Button } from "@/components/ui/button";

export function CommunityCard() {
  return (
    <div 
      className="chart-card overflow-hidden animate-fade-in relative"
      style={{ animationDelay: "0.5s" }}
    >
      {/* Background Decoration */}
      <div className="absolute -right-8 -bottom-8 w-32 h-32 rounded-full bg-gradient-to-br from-accent/30 to-cyan-400/30" />
      <div className="absolute -right-4 -bottom-4 w-24 h-24 rounded-full bg-gradient-to-br from-accent/40 to-cyan-400/40" />
      
      <div className="relative z-10">
        <h3 className="text-lg font-semibold text-foreground mb-2">
          Join the community and find out more...
        </h3>
        <Button variant="outline" className="mt-2">
          Explore now
        </Button>
      </div>
    </div>
  );
}
