export interface Image360 {
    title: string;
    url: string;
    type?: 'url' | 'upload';
}

export interface Department {
    id: number;
    code: string;
    name: string;
    color?: string;
    sort_order: number;
    status: string;
    description?: string;
}

export interface Official {
    id: number;
    name: string;
    role: string;
    phone: string;
    place_id?: number;
    neighborhood_name?: string | string[];
    avatar_color?: string;
    avatar?: string;
    department?: string;
    status?: string;
}

export interface Place {
    id: number;
    name: string;
    category: 'government' | 'neighborhood' | 'school' | 'health' | 'police' | 'meritorious_family';
    status: 'active' | 'closed';
    address?: string;
    phone?: string;
    lat: number;
    lng: number;
    image?: string | null;
    administrative_unit_id?: number;
    description?: string;
    hours?: string;
    households?: number;
    population?: number;
    former_names?: string;
    cultural_house_address?: string;
    images_360?: Image360[];
    officials?: Official[];
}

export interface AdminUnit {
    id: number;
    code: string;
    name: string;
    type: string;
    lat: number;
    lng: number;
    district_name?: string | null;
}

export interface Province {
    code: string;
    name: string;
    full_name: string;
    latitude: number | null;
    longitude: number | null;
}

export interface Neighborhood {
    id: number;
    name: string;
    type: 'old' | 'new';
    group_code: string;
    leader_name: string | null;
    leader_phone: string | null;
    households: number;
    people: number;
    area_ha?: number;
    status?: string;
}

export interface MeritoriousFamily {
    id: number;
    name: string;
    file_path?: string | null;
    file_url?: string | null;
    file_name?: string | null;
    file_size?: string | null;
    description?: string | null;
    period_date?: string | null;
    created_at?: string | null;
    status: string;
    representative_name?: string;
    type?: string;
    phone?: string;
    address?: string;
    celebration_event_id?: number | null;
    neighborhood_id?: number | null;
    benefit_details?: string;
}

export interface CelebrationEvent {
    id: number;
    name: string;
    day: number;
    month: number;
    description: string;
    status: string;
    title?: string;
    message?: string;
    is_active?: boolean;
}

export interface PortalStats {
    totalPlaces: number;
    totalNeighborhoods: number;
    totalHouseholds: number;
    totalPopulation: number;
    naturalArea: string;
    totalVrScenes: number;
}

